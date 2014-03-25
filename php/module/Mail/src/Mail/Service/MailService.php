<?php
/**
 * MailService
 * User: etienne_odiwi
 */
namespace Mail\Service;

use Gitlab\Client;
use Gitlab\Model\Project;
use Html2Text\Html2Text;
use Zend\Mail\Storage\Imap;
use RecursiveIteratorIterator;
use Zend\Mail\Exception;
use Zend\Mail;
use Zend\Mime;
use Zend\ServiceManager\ServiceManager;

class MailService
{
    /**
     * Consts IMAP
     */
    private static $HOST;
    private static $USER;
    private static $PASSWORD;
    const TREATED_FOLDER_NAME = "treated issues";
    const UNTREATED_FOLDER_NAME = "untreated issues";
    const VALID_FOLDER_NAME = "valid";
    const UNVALID_FOLDER_NAME = "unvalid";

    /**
     * Consts Gitlab
     */
    private static $GITLAB_HOST;
    private static $GITLAB_TOKEN;
    private static $GITLAB_USER_NAME;

    private $sm;
    private $projectTable;
    private $mails;
    private $client;

    public function __construct(ServiceManager $sm) {
        $this->sm = $sm;
        $config = $this->sm->get('config');
        //imap
        self::$HOST = $config['mail']['imap']['host'];
        self::$USER = $config['mail']['imap']['user'];
        self::$PASSWORD = $config['mail']['imap']['password'];
        //gitlab
        self::$GITLAB_HOST = $config['mail']['gitlab']['host'];
        self::$GITLAB_TOKEN = $config['mail']['gitlab']['token'];
        self::$GITLAB_USER_NAME = $config['mail']['gitlab']['user'];
    }

    /**
     * Synchronise les issue gitlab avec les mails contenu dans la table mail_project
     */
    public function syncIssueWithMail() {
        //fetch all the msgs
        $this->fetchMail();

        //connect the gitlab api
        $this->connectGitlabClient();

        for ($i = count($this->mails); $i; --$i) {
            $contentType = strtok($this->mails[$i]->contentType, ';');
            $isHtml = ($contentType == 'text/html') ? true : false;

            $from = strtolower(preg_replace("/(.*?)<|>/", "", $this->mails[$i]->getHeaderField('from')));
            $arrTo = (explode(",", str_replace("\r\n", "", strtolower(preg_replace("/(.*?)<|>/", "", $this->mails[$i]->getHeaderField('to'))))));

            $projects = $this->getProjectTable()->getIdGitlabProjectWithMails($from, $arrTo);
            $mailAliases = false;
            // si cet email a des ids de projet gitlab
            if (count($projects) > 0) {
                // on boucle sur les mails correspondant aux infos du mail
                foreach ($projects as $project) {
                    $absFolderName = self::TREATED_FOLDER_NAME .'/'. $project->folderName .'/'. self::VALID_FOLDER_NAME;
                    $this->createFolder($absFolderName);
                    // si le message contient plusieurs parties (pieces jointes, text/plain, text/hml, ...)
                    if ($this->mails[$i]->isMultipart()) {
                        // on boucle sur ses parties
                        foreach (new RecursiveIteratorIterator($this->mails[$i]) as $part) {
                            try {
                                // si c'est du 'text/plain'
                                if (strtok($part->contentType, ';') == 'text/plain') {
                                    // on pousse le ticket dans le bon projet grace au mail du destinataire
                                    $issue = $this->addIssue($project->idGitlabProject, false, $this->mails[$i]->subject, $part->getContent());
                                    // puis on deplace le mail dans un autre dossier pour ne pas le retraiter
                                    if ($issue) {
                                        $this->mails->copyMessage($i, $absFolderName);
                                        $this->sendMail(true, $from, $project->projectName, $issue->iid);
                                    }
                                }
                            } catch (Exception $e) {
                                // ignore
                            }
                        }
                    // sinon on verifie juste que son unique partie soit du text ou de l'html
                    } else if ($contentType == 'text/plain' || $isHtml) {
                        // on pousse le ticket dans le bon projet grace au mail du destinataire
                        $issue = $this->addIssue($project->idGitlabProject, $isHtml, $this->mails[$i]->subject, $this->mails[$i]->getContent());
                        // puis on deplace le mail dans un autre dossier pour ne pas le retraiter
                        if ($issue) {
                            $this->mails->copyMessage($i, $absFolderName);
                            $this->sendMail(true, $from, $project->projectName, $issue->iid);
                        }
                    }
                }
                $this->mails->removeMessage($i);
            } else {
                $mailAliases = $this->getProjectTable()->getFolderNameWithAlias($arrTo);
            }

            // si cet email n'a pas de projet gitlab
            if ($mailAliases) {
                // si cet email a ete envoye avec un alias valide
                if (count($mailAliases) > 0) {
                    // on boucle sur les alias
                    foreach ($mailAliases as $mailAlias) {
                        $absFolderName = self::TREATED_FOLDER_NAME .'/'. $mailAlias->folderName.'/' . self::UNVALID_FOLDER_NAME;
                        $this->createFolder($absFolderName);
                        $this->mails->copyMessage($i, $absFolderName);
                    }
                    $this->mails->removeMessage($i);
                    $this->sendMail(false, $from);
                // sinon
                } else {
                    $this->createFolder(self::UNTREATED_FOLDER_NAME);
                    $this->mails->moveMessage($i, self::UNTREATED_FOLDER_NAME);
                }
            }
        }
    }

    /**
     * On recupere le wrapper pour la table project
     * @return mixed
     */
    private function getProjectTable() {
        if (!$this->projectTable) {
            $this->projectTable = $this->sm->get('Mail\Model\ProjectTable');
        }
        return $this->projectTable;
    }

    /**
     * Check si le folder dans la boite mail existe deja
     * @param $name
     * @param null $folders
     * @return bool
     */
    private function hasFolder($name, $folders=null) {
        if ($folders == null) $folders = $this->mails->getFolders();
        $name = explode('/', $name);
        foreach ($folders as $folder) {
            if ($folder->getLocalName() === $name[0]) {
                if (count($name) > 1) {
                    if (!$folder->isLeaf()) {
                        array_shift($name);
                        return $this->hasFolder(implode('/', $name), $folder);
                    }
                    return false;
                }
                return true;
            }
        }
        return false;
    }

    /**
     * On cree le dossier apres avoir verifie si le folder dans la boite mail existe deja
     * @param $name
     */
    private function createFolder($name) {
        if (!$this->hasFolder($name)) {
            $this->mails->createFolder($name);
        }
    }

    /**
     * Recupere les mails via Imap
     */
    private function fetchMail() {
        //fetch all the msgs
        $this->mails = new Imap(array(
            'host'     => self::$HOST,
            'user'     => self::$USER,
            'password' => self::$PASSWORD,
            'ssl'      => 'TLS'
        ));
    }

    /**
     * Connection a l'api gitlab via le token
     */
    private function connectGitlabClient() {
        //instanciate the Gitlab Client connection
        $this->client = new Client(self::$GITLAB_HOST);
        $this->client->authenticate(self::$GITLAB_TOKEN, Client::AUTH_HTTP_TOKEN, self::$GITLAB_USER_NAME);
    }

    /**
     * Ajoute une issue via l'api gitlab
     * @param $idProject
     * @param $isHtml
     * @param $title
     * @param $description
     * @return bool
     */
    private function addIssue($idProject, $isHtml, $title, $description) {
        $project = new Project($idProject, $this->client);

        if ($isHtml) {
            $h2t = new html2text($description);
            $description = $h2t->get_text();
        }

        $issue = $project->createIssue($title, array(
            'description' => utf8_encode(quoted_printable_decode($description)),
        ));

        if (!empty($issue))
            return $issue;
        else
            return false;
    }

    /**
     * Envoie un mail de reponse
     * @param $treated
     * @param $to
     * @param null $projectName
     * @param null $idIssue
     */
    private function sendMail($treated, $to, $projectName=null, $idIssue=null) {
        $mail = new Mail\Message();
        $mail->setFrom(self::$USER);
        $mail->addTo($to);
        $mail->setEncoding("UTF-8");

        $viewRenderer = $this->sm->get('ViewRenderer');

        if ($treated) {
            $mail->setSubject("Incident enregistré - $projectName #$idIssue");
            $content = $viewRenderer->render('mail/bash/_tplok', array('projectName'=>$projectName, 'idIssue'=>$idIssue));
            $html = new Mime\Part($content);
        } else {
            $mail->setSubject("Incident refusé - mail refusé");
            $content = $viewRenderer->render('mail/bash/_tplnok', null);
            $html = new Mime\Part($content);
        }
        $html->type = "text/html";

        $body = new Mime\Message();
        $body->setParts(array($html));

        $mail->setBody($body);

        // Setup SMTP transport using LOGIN authentication
        $transport = new Mail\Transport\Smtp();
        $options   = new Mail\Transport\SmtpOptions(array(
            'name'              => self::$HOST,
            'host'              => self::$HOST,
            'port'              => 587, // Notice port change for TLS is 587
            'connection_class'  => 'login',
            'connection_config' => array(
                'username' => self::$USER,
                'password' => self::$PASSWORD,
                'ssl'      => 'tls',
            ),
        ));
        $transport->setOptions($options);

        $transport->send($mail);
    }
}