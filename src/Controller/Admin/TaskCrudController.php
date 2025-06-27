<?php

namespace App\Controller\Admin;

use App\Entity\BugReport;
use App\Entity\Task;
use App\Repository\TaskRepository;
use App\Services\TaskService;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TimeField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

class TaskCrudController extends AbstractCrudController
{


    private TaskRepository $repository;

    private EntityManagerInterface $entityManager;
    private AdminUrlGenerator $adminUrlGenerator;

    private TaskService $service;

    public function __construct(TaskRepository $repository , TaskService $service, AdminUrlGenerator $adminUrlGenerator, EntityManagerInterface $entityManager) {
        $this->repository = $repository;
        $this->service = $service;
        $this->entityManager = $entityManager;
        $this->adminUrlGenerator = $adminUrlGenerator;
    }
    public static function getEntityFqcn(): string
    {
        return Task::class;
    }





    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('name'),
            TextAreaField::new('description'),
            AssociationField::new('project'),
            AssociationField::new('tracker'),
            AssociationField::new('status'),
            AssociationField::new('priority'),
            AssociationField::new('bugReports'),
            DateTimeField::new('from_date')->setFormat("d MMM yyyy"),
            DateTimeField::new('deadline')->setFormat("d MMM yyyy"),
            AssociationField::new('developer'),
            AssociationField::new('created_by'),
            IntegerField::new('reach')->onlyOnForms(),
            IntegerField::new('confidence')->onlyOnForms(),
            IntegerField::new('effort')->onlyOnForms(),
        ];
    }

    public function createEntity(string $entityFqcn){
        $task = new Task();
        $bug_report_id = $this->getContext()->getRequest()->query->get('bug_report_id');
        if ($bug_report_id) {
            $bug_report = $this->entityManager->getRepository(BugReport::class)->find($bug_report_id);
            $task->addBugReport($bug_report);
        }
        return $task;
    }

    public function viewBugReports(AdminUrlGenerator $adminUrlGenerator)
    {
        // Получаем ID текущей задачи
        $taskId = $this->getContext()->getRequest()->query->get('entityId');
        // Генерируем URL для списка BugReport с фильтром по текущей задаче
        $url = $adminUrlGenerator
            ->setController(BugReportCrudController::class)
            ->setAction(Action::INDEX)
            ->set('query', ['task' => $taskId]) // предполагается, что у BugReport есть поле task
            ->generateUrl();

        return $this->redirect($url);
    }

    public function configureActions(Actions $actions): Actions{

        $viewBugReports = Action::new('viewBugReports', 'Bug Reports')
            ->linkToUrl(function(Task $task)
            {return $this->adminUrlGenerator
                ->setController(BugReportCrudController::class)
                ->setAction(Action::INDEX)
                ->set( 'task_id', $task->getId()) // предполагается, что у BugReport есть поле task
                ->generateUrl();}
    );
        return $actions->add(Action::INDEX, $viewBugReports);
    }
}
