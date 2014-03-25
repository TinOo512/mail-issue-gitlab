<?php
/**
 * Project Controller
 */
namespace Mail\Controller;

use Mail\Model\Project;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class ProjectController extends AbstractRestfulController
{
    public function get($id)
    {
        $serviceManager = $this->getServiceLocator();
        $projectService = $serviceManager->get('Mail\Model\ProjectTable');
        return new JsonModel($projectService->getProject($id, true));
    }

    public function getList()
    {
        $serviceManager = $this->getServiceLocator();
        $projectService = $serviceManager->get('Mail\Model\ProjectTable');
        return new JsonModel($projectService->fetchAll(true));
    }

    public function create($data)
    {
        $serviceManager = $this->getServiceLocator();
        $projectService = $serviceManager->get('Mail\Model\ProjectTable');

        $project = new Project($data);
        if ($projectService->saveProject($project)) {
            return new JsonModel(array('success'=>true));
        }
        return new JsonModel(array('success'=>false));
    }

    public function update($id, $data)
    {
        $serviceManager = $this->getServiceLocator();
        $projectService = $serviceManager->get('Mail\Model\ProjectTable');

        $project = new Project($data);
        if ($projectService->updateProject($id, $project)) {
            return new JsonModel(array('success'=>true));
        }
        return new JsonModel(array('success'=>false));
    }

    public function delete($id)
    {
        $serviceManager = $this->getServiceLocator();
        $projectService = $serviceManager->get('Mail\Model\ProjectTable');

        if ($projectService->deleteProject($id)) {
            return new JsonModel(array('success'=>true));
        }
        return new JsonModel(array('success'=>false));
    }
}