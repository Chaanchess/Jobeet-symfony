<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Job;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use App\Repository\JobRepository;
use App\Form\JobType;
use App\Service\FileUploader;


class JobController extends AbstractController{
    /**
     * List all job entities.
     * 
     * @Route("/", name="job.list")
     * 
     * @return Response
     */

    public function list(EntityManagerInterface $em): Response{
        //$jobs = $this->getDoctrine()->getRepository(Job::class)->findAll(); MUESTRA TODOS LOS TRABAJOS

        //$jobs = $em->getRepository(Job::class)->findActiveJobs();

        $categories = $em->getRepository(Category::class)->findWithActiveJobs();

        return $this->render('job/list.html.twig',[
            //'jobs'=>$jobs,
            'categories'=>$categories,
        ]);

    }

      /**
     * Finds and display a job entity.
     * 
     * @Route("/job/{id}", name="job.show", methods="GET", requirements={"id" = "\d+"})
     * 
     * @Entity("job", expr="repository.findActiveJobs(id)")
     * 
     * @param Job $job
     * 
     * @return Response
     */

    public function show(Job $job) : Response{
        return $this->render('job/show.html.twig',[
            'job'=>$job,
        ]);

     }

     /**
     * Creates a new job entity.
     *
     * @Route("/job/create", name="job.create", methods={"GET", "POST"})
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     *
     * @return RedirectResponse|Response
     */
    public function create(Request $request, EntityManagerInterface $em) : Response
    {
        $job = new Job();
        $form = $this->createForm(JobType::class, $job);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile|null $logoFile */
            $logoFile = $form->get('logo')->getData();

            if ($logoFile instanceof UploadedFile) {
                $fileName = $fileUploader->upload($logoFile);

                $job->setLogo($fileName);
            }

            $em->persist($job);
            $em->flush();

            return $this->redirectToRoute('job.list');
        }
        
        return $this->render('job/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Edit existing job entity
     * 
     * @Route("/job/{token}/edit", name="job.edit", methods={"GET", "POST"}, requirements={"token" = "\w+"})
     * 
     * @param Request $request
     * @param Job $job
     * @param EntityManagerInterface $em
     * 
     * @return Response
     */
    public function edit(Request $request, Job $job, EntityManagerInterface $em) : Response
    {
        $form = $this->createForm(JobType::class, $job);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('job.list');
        }
        
        return $this->render('job/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
 
}
?>