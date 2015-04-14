<?php

namespace Works\CommonBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Works\CommonBundle\DependencyInjection\Interkassa\InterkassaShop;

use Works\CommonBundle\Entity\Job;
use Works\CommonBundle\Entity\Payment;
use Works\CommonBundle\Form\JobType;

/**
 * Job controller.
 *
 */
class PaymentController extends Controller
{
    /**
     * Generate interkassa shop class.
     */
    private function generateShop()
    {
        return InterkassaShop::factory(array(
                    'id' => $this->container->getParameter('interkassa_id'),
                    'secret_key' => $this->container->getParameter('interkassa_secret'),
                    'status_url' => $this->getRequest()->getUriForPath($this->generateUrl('payment_status'))
        ));
    }
    /**
     * Generate interkassa payment class.
     */
    private function generatePayment($days, $job) 
    {
        $cost = $this->container->getParameter('interkassa_cost');

        $shop = $this->generateShop();
        
        $em = $this->getDoctrine()->getManager();
        $exist = $em->getRepository('WorksCommonBundle:Payment')->checkHash($job);

        if($exist) {
            $id = $exist->getHash();
        } else {
            $id = md5(uniqid(rand(), true));
            $payment = new Payment;
            $payment->setHash($id);
            $payment->setJob($job);
            $em->persist($payment);
            $em->flush();
        }
        
        return $shop->createPayment(array(
            'id' => $id,
            'amount' => $cost * $days,
            'description' => 'VIP-status for job ' . $job->getTitle() . ' to ' . $days . ' days'
        ));
        
    }
    /**
     * Buy vip-place for job.
     */
    public function buyAction(Request $request, $id)
    {
        $job = $this->getDoctrine()
            ->getRepository('WorksCommonBundle:Job')->getByPk($id);
        
        if(!$job || $job->getUser()->getId() != $this->getUser()->getId()) {
            throw $this->createNotFoundException("The job don't exists or not allowed.");
        }
        
        $form = $this->createFormBuilder(array())
                ->add('count', 'integer', array(
                         'label' => 'Days count',
                         'data' => 1))
                ->getForm();
        
        if($request->isMethod('POST')) {
            $form->bind($request);
            $days = $form->getData()['count'];
            $shop = $this->generatePayment($days, $job);
            
            return $this->render('WorksCommonBundle:Payment:submit_payment.html.twig', array('job'=>$job, 'days'=>$days, 'payment'=>$shop));
        }
        
        return $this->render('WorksCommonBundle:Payment:payment.html.twig', array('job'=>$job, 'form'=>$form->createView()));
    }
    
    /**
     * For callback-payment.
     */
    public function statusAction(Request $request)
    {
        $shop = $this->generateShop();
        $status = $shop->receiveStatus($_POST);

        if(!$status){
            throw new Symfony\Component\HttpKernel\Exception\HttpException(400);
        }
        
        $hash = $status->getTransId();
        $this->getDoctrine()->getManager()->getRepository('WorksCommonBundle:Payment')->commitPayment($hash);
        
        return new Response("");
    }
    
    /**
     * Show success action.
     */
    public function successAction(Request $request)
    {   
        return $this->render('WorksCommonBundle:Payment:success.html.twig');
    }   

    /**
     * Show success action.
     */
    public function failedAction(Request $request)
    {   
        return $this->render('WorksCommonBundle:Payment:failed.html.twig');
    }
}
