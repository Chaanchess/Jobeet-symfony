<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Job;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use App\Repository\JobRepository;
use App\Form\JobType;


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
     * @Route("/job/create", name="job.create", methods="GET")
     *
     * @return Response
     */
    public function create() : Response
    {
        $job = new Job();
        $form = $this->createForm(JobType::class, $job);

        return $this->render('job/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
?>