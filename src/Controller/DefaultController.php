<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends AbstractController
{

    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @Route("/", name="default")
     */
    public function index(Request $request)
    {
        $form = $this->createFormBuilder()
                    ->add('url', TextType::class)
                    ->setMethod('GET')
                    ->getForm();


        $subtitles ="";

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $response = $this->client->request(
                'GET',
                $form->get('url')->getData()
            );
    
            $contents = $response->getContent();
    
            $data = json_decode($contents, true);
    
            foreach($data['events'] as $event){
                if(isset($event['segs'])){
                    foreach($event['segs'] as $text){
                        $subtitles.= $text['utf8']. " ";
                    }
                }
            }
        }

        return $this->render('default/index.html.twig', [
            'form' => $form->createView(),
            'text' => $subtitles
        ]);
    }
}
