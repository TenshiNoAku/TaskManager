<?php

namespace App\Controller\Admin;

use App\Entity\BugReport;
use App\Repository\BugReportRepository;
use App\Services\BugReportService;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\BatchActionDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class BugReportCrudController extends AbstractCrudController
{

    private BugReportRepository $repository;

    private AdminUrlGenerator $adminUrlGenerator;
    private Security $security;
    private HttpClientInterface $httpClient;
    private BugReportService $service;

    public function __construct(BugReportRepository $repository , BugReportService $service, AdminUrlGenerator $adminUrlGenerator, Security $security,HttpClientInterface $httpClient) {
        $this->repository = $repository;
        $this->service = $service;
        $this->security = $security;
        $this->adminUrlGenerator = $adminUrlGenerator;
        $this->httpClient = $httpClient;
    }
    public static function getEntityFqcn(): string
    {
        return BugReport::class;
    }



    public function compare(AdminContext $context)
    {
        $query = $context->getRequest()->query;
        $entityId = $context->getRequest()->query->get('entityId');
        $bug1 = $this->repository->findOneBy(['id' => $entityId]);
        $bug2 = $bug1->getIsDuplicateOf();

        return $this->render('admin/compare.html.twig', [
            'entity1' => $bug1,
            'entity2' => $bug2,
        ]);
    }


    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {

        $queryBuilder = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);
        $request = $this->getContext()->getRequest();
        if ($request->query->get('duplicates') !== null) {
            $queryBuilder = $this->repository->getUnresolvedDuplicates();
        }

        if ($request->query->get('new_reports') !== null) {
            $queryBuilder = $this->repository->getUnresolvedReports();
        }

        if ($request->query->get('task_id') !== null) {
            $queryBuilder = $this->repository->getFilteredByTask($request->query->get('task_id'));
        }

        return $queryBuilder;
    }



    public function configureActions(Actions $actions): Actions
    {
        $compareAction = Action::new('compare', 'Compare')
            ->linkToCrudAction('compare')
            ->addCssClass('btn btn-secondary')
            ->displayIf(
            static function ($entity) {
                return $entity->getIsDuplicateOf() !== null && $entity->getTask() === null;
            });


        $approveAction = Action::new('approve', 'Approve')
            ->linkToCrudAction('approve')
            ->displayIf(
                static function ($entity) {
                    return $entity->getIsDuplicateOf() !== null && $entity->getTask() === null;
                }
        );

        $declineAction = Action::new('decline', 'Decline')
            ->linkToCrudAction('decline')
            ->displayIf(
                static function ($entity) {
                    return $entity->getIsDuplicateOf() !== null && $entity->getTask() === null;
                }
            );

        $createTask = Action::new('createTask', 'Add Task')
            ->linkToUrl(function (BugReport $bugReport) {
                return $this->adminUrlGenerator
                    ->setController(TaskCrudController::class)
                    ->setAction('new')
                    ->set('bug_report_id', $bugReport->getId())

                    ->generateUrl();
            }) ->displayIf(
                static function ($entity) {
                    return $entity->getIsDuplicateOf() === null && $entity->getTask() === null;
                });
        return $actions
            ->add(Crud::PAGE_INDEX, $approveAction)
            ->add(Crud::PAGE_INDEX, $declineAction)
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_INDEX, $createTask)
            ->add(Crud::PAGE_INDEX, $compareAction);

    }

    public function approve(AdminContext $context){
        ;
        $entityId = $context->getRequest()->query->get('entityId');

        $entity = $this->service->approveDuplicate($entityId);
        return $this->redirectToRoute('admin_bug_report_index',['duplicates'=>true]);
    }

    public function decline(AdminContext $context){
        ;
        $entityId = $context->getRequest()->query->get('entityId');

        $entity = $this->service->declineDuplicate($entityId);
        return $this->redirectToRoute('admin_bug_report_index',['duplicates'=>true]);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('task'),
            AssociationField::new('is_duplicate_of'),
            TextField::new('name'),
            TextareaField::new('text'),

        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)->setPaginatorPageSize(12); // TODO: Change the autogenerated stub
    }


    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $newRequest = ["q1"=>$entityInstance->getText()];
        $response = $this->httpClient->request(
            'POST',
            'http://127.0.0.1:5000/items/',
            ['headers' => [
                'Content-Type' => 'application/json',
            ],'json'=>$newRequest])?->toArray();
        if (isset($response['id'])) {
            $entityInstance->setIsDuplicateOf($this->repository->findOrFail((int)$response['id']));
        }
        parent::persistEntity($entityManager,$entityInstance);
    }

}
