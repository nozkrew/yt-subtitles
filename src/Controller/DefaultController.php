<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
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
     * @Route("/", name="index")
     */
    public function indexAction()
    {
        return $this->render('base.html.twig', []);
    }

    /**
     * @Route("/subtiltes", name="default")
     */
    public function index(Request $request)
    {

        $response = $this->client->request(
            'GET',
            $request->query->get('url')
        );

        $contents = $response->getContent();

        $data = json_decode($contents, true);

        $subtitles ="";

        foreach($data['events'] as $event){
            if(isset($event['segs'])){
                foreach($event['segs'] as $text){
                    if($text['utf8'] != "\n"){
                        $subtitles.= $text['utf8']. " ";
                    }                    
                }
            }
        }
       
        return new JsonResponse([
            'text' => $subtitles
        ]);
    }
}
