<?php

namespace App\Controller\Front;

use App\Entity\Brand;
use App\Entity\CancelBook;
use App\Entity\Conversation;
use App\Entity\Image;
use App\Entity\League;
use App\Entity\Player;
use App\Entity\Product;
use App\Entity\Sport;
use App\Entity\Team;
use App\Form\Front\ProductFormType;
use App\Repository\BrandRepository;
use App\Repository\CancelBookRepository;
use App\Repository\ColorRepository;
use App\Repository\ConversationRepository;
use App\Repository\ImageRepository;
use App\Repository\LeagueRepository;
use App\Repository\PlayerRepository;
use App\Repository\ProductRepository;
use App\Repository\ProductViewRepository;
use App\Repository\SportRepository;
use App\Repository\TeamRepository;
use App\Repository\TextilRepository;
use App\Repository\UserRepository;
use App\Service\AlertService;
use App\Service\ImageOptimizer;
use App\Service\MessageStepService;
use App\Service\NotificationService;
use App\Service\ViewService;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/product')]
class ProductController extends AbstractController
{
    #[Route('/new', name: 'app_front_product_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProductRepository $productRepository, LeagueRepository $leagueRepository, TeamRepository $teamRepository,
                        PlayerRepository $playerRepository, ImageRepository $imageRepository, SportRepository $sportRepository, BrandRepository $brandRepository,
                        ImageOptimizer $imageOptimizer, ColorRepository $colorRepository, TextilRepository $textilRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $product = new Product();
        $form = $this->createForm(ProductFormType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setUser($this->getUser());
            $product->setUuid(Uuid::uuid4()->toString());
            $product->setCreatedAt(new \DateTimeImmutable());
            $product->setUpdatedAt(new \DateTimeImmutable());

            $statement = 'A valider';

            // Traitement du sport
            $sportProduct = $_POST['search-input-sport'] ?? null;
            if (!empty($sportProduct)) {
                $sportInBase = $sportRepository->findOneBy(['title'=>$sportProduct],[]);
                if($sportInBase === null){
                    $newSport = new Sport();
                    $newSport
                        ->setTitle(ucfirst($sportProduct))
                        ->setDisplayMenu(0)
                        ->setAvailable(0);
                    $sportRepository->add($newSport);
                    $product->setSport($newSport);
                }
                else {
                    $product->setSport($sportInBase);
                }
            }

            // Traitement de la ligue
            $leagueProduct = $_POST['search-input-league'] ?? null;
            if (!empty($leagueProduct)) {
                $leagueInBase = $leagueRepository->findOneBy(['title'=>$leagueProduct],[]);
                if($leagueInBase === null){
                    $newLeague = new League();
                    $newLeague
                        ->setTitle(ucfirst($leagueProduct))
                        ->setAvailable(0);
                    $leagueRepository->add($newLeague);
                    $product->setLeague($newLeague);
                }
                else {
                    $product->setLeague($leagueInBase);
                }
            }

            // Traitement de l'équipe
            $teamProduct = $_POST['search-input-team'] ?? null;
            if (!empty($teamProduct)) {
                $teamInBase = $teamRepository->findOneBy(['title'=>$teamProduct],[]);
                if($teamInBase === null){
                    $newTeam = new Team();
                    $newTeam
                        ->setTitle(ucfirst($teamProduct))
                        ->setAvailable(0);
                    $teamRepository->add($newTeam);
                    $product->setTeam($newTeam);
                }
                else {
                    $product->setTeam($teamInBase);
                }
            }

            // Traitement du joueur
            $playerProduct = $_POST['search-input-player'] ?? null;
            if (!empty($playerProduct)) {
                $playerInBase = null;
                $playersInBase = $playerRepository->findAll();
                foreach ($playersInBase as $player){
                    $playerNameComplete = $player->getLastName().' '.$player->getFirstName();
                    if($playerNameComplete == $playerProduct){
                        $playerInBase = $player;
                    }
                }
                if($playerInBase === null){
                    $newPlayer = new Player();
                    $newPlayer
                        ->setTemporaryName($playerProduct)
                        ->setAvailable(0);
                    $playerRepository->add($newPlayer);
                    $product->setPlayer($newPlayer);
                }
                else {
                    $product->setPlayer($playerInBase);
                }
            }

            // Traitement de la marque
            $brandProduct = $_POST['search-input-brand'] ?? null;
            if (!empty($brandProduct)) {
                $brandInBase = $brandRepository->findOneBy(['title'=>$brandProduct],[]);
                if($brandInBase === null){
                    $newBrand = new Brand();
                    $newBrand
                        ->setTitle(ucfirst($brandProduct))
                        ->setAvailable(0);
                    $brandRepository->add($newBrand);
                    $product->setBrand($newBrand);
                }
                else {
                    $product->setBrand($brandInBase);
                }
            }

            //Traitement des couleurs
            $colorsProduct = $_POST['colors'] ?? null;
            if (!empty($colorsProduct)) {
                foreach ($colorsProduct as $colorProduct){
                    $product->addColor($colorRepository->find($colorProduct));
                }
            }

            //Traitement des textils
            $textilsProduct = $_POST['textils'] ?? null;
            if (!empty($textilsProduct)) {
                foreach ($textilsProduct as $textilProduct){
                    $product->addTextil($textilRepository->find($textilProduct));
                }
            }

            $product->setStatement($statement);
            $productRepository->add($product);

            $files = array_filter($_FILES['images']['name']);
            $total_count = count($_FILES['images']['name']);
            for( $i=0 ; $i < $total_count ; $i++ ) {
                $tmpFilePath = $_FILES['images']['tmp_name'][$i];
                if ($tmpFilePath != ""){
                    $newFilePath = "./upload/product/img/" . $_FILES['images']['name'][$i];
                    if(move_uploaded_file($tmpFilePath, $newFilePath)) {
                        $image = new Image();
                        $image->setTitle($_FILES['images']['name'][$i]);
                        $image->setUrl($_FILES['images']['name'][$i]);
                        $image->setType('product');
                        $image->setProduct($product);
                        $imageRepository->add($image);
                        $imageOptimizer->resize('product',$newFilePath);
                    }
                }
            }
            return $this->redirectToRoute('app_front_product_confirm', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('front/product/new.html.twig', [
            'product' => $product,
            'leagues' => $leagueRepository->findAll(),
            'teams' => $teamRepository->findAll(),
            'players' => $playerRepository->findAll(),
            'form' => $form->createView(),
        ]);
    }

    #[Route('/confirm', name: 'app_front_product_confirm')]
    public function confirm(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        return $this->render('front/product/confirm.html.twig', [
        ]);
    }

    #[Route('/{uuid}', name: 'app_front_product_show', methods: ['GET', 'POST'])]
    public function show($uuid, ProductRepository $productRepository, ProductViewRepository $productViewRepository): Response
    {
        $product = $productRepository->findOneBy(['uuid'=>$uuid]);

        $view = new ViewService();
        $view->setViewProduct($this->getUser(),$product,$productViewRepository);

        return $this->render('front/product/show.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/{uuid}/contact', name: 'app_front_product_contact', methods: ['GET', 'POST'])]
    public function contact($uuid, ProductRepository $productRepository, ConversationRepository $conversationRepository, UserRepository $userRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $product = $productRepository->findOneBy(['uuid'=>$uuid]);
        $conversationsUser = $this->getUser()->getConversations();
        $exist = false;
        foreach ($conversationsUser as $conversation){
            if($conversation->getProduct()->getId() == $product->getId()){
                $exist = true;
                return $this->redirectToRoute('app_front_conversation_show', ['uuid'=>$conversation->getUuid()], Response::HTTP_SEE_OTHER);
            }
        }
        if($exist == false){
            $conversation = new Conversation();
            $conversation->setUuid(Uuid::uuid4()->toString());
            $conversation->setProduct($product);
            $conversation->setCreatedAt(new \DateTimeImmutable());
            $conversation->setUpdatedAt(new \DateTimeImmutable());
            $conversation->setRemove(1);
            $conversationRepository->add($conversation);

            $userProduct = $product->getUser();
            $userProduct->addConversation($conversation);
            $userRepository->add($userProduct);

            $user = $this->getUser();
            $user->addConversation($conversation);
            $userRepository->add($user);

            return $this->redirectToRoute('app_front_conversation_show', ['uuid'=>$conversation->getUuid()], Response::HTTP_SEE_OTHER);
        }
    }

    #[Route('/{uuid}/book', name: 'app_front_product_book', methods: ['GET', 'POST'])]
    public function book($uuid, ProductRepository $productRepository, ConversationRepository $conversationRepository, UserRepository $userRepository,
                         MessageStepService $messageStepService): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $product = $productRepository->findOneBy(['uuid'=>$uuid]);
        $conversationsUser = $this->getUser()->getConversations();
        $exist = false;
        foreach ($conversationsUser as $conversation){
            if($conversation->getProduct()->getId() == $product->getId()){
                $exist = true;
                $product->setBuyer($this->getUser());
                $product->setStatement('Réservé');
                $productRepository->add($product);

                $conversation->setRemove(0);
                $conversationRepository->add($conversation);

                $messageStepService->addMessageStep('Book',$conversation);

                return $this->redirectToRoute('app_front_conversation_show', ['uuid'=>$conversation->getUuid()], Response::HTTP_SEE_OTHER);
            }
        }
        if($exist == false){
            $conversation = new Conversation();
            $conversation->setUuid(Uuid::uuid4()->toString());
            $conversation->setProduct($product);
            $conversation->setCreatedAt(new \DateTimeImmutable());
            $conversation->setUpdatedAt(new \DateTimeImmutable());
            $conversation->setRemove(0);
            $conversationRepository->add($conversation);

            $userProduct = $product->getUser();
            $userProduct->addConversation($conversation);
            $userRepository->add($userProduct);

            $user = $this->getUser();
            $user->addConversation($conversation);
            $userRepository->add($user);

            $product->setBuyer($this->getUser());
            $product->setStatement('Réservé');
            $productRepository->add($product);

            $messageStepService->addMessageStep('Book',$conversation);

            return $this->redirectToRoute('app_front_conversation_show', ['uuid'=>$conversation->getUuid()], Response::HTTP_SEE_OTHER);
        }
    }

    #[Route('/{uuid}/valid_book', name: 'app_front_product_valid_book', methods: ['GET', 'POST'])]
    public function validlBook($uuid, ProductRepository $productRepository, ConversationRepository $conversationRepository,
                               MessageStepService $messageStepService): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $product = $productRepository->findOneBy(['uuid'=>$uuid]);
        $user = $this->getUser();

        //We check if the person who book is the person accessing this url
        if($product->getBuyer() != null and $product->getBuyer()->getId() == $user->getId()){
            $product->setStatement("Vendu");

            foreach ($user->getConversations() as $conversation){
                if($conversation->getProduct()->getId() == $product->getId()){
                    $conversation->setUpdatedAt(new \DateTimeImmutable());
                    $conversationRepository->add($conversation);
                    $messageStepService->addMessageStep('ConfirmBook',$conversation);
                    return $this->redirectToRoute('app_front_conversation_show', ['uuid'=>$conversation->getUuid()], Response::HTTP_SEE_OTHER);
                }
            }
        }
    }

    #[Route('/{uuid}/cancel_book', name: 'app_front_product_cancel_book', methods: ['GET', 'POST'])]
    public function cancelBook($uuid, ProductRepository $productRepository, CancelBookRepository $cancelBookRepository, ConversationRepository $conversationRepository,
                               MessageStepService $messageStepService): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $product = $productRepository->findOneBy(['uuid'=>$uuid]);
        $user = $this->getUser();

        //We check if the person who book is the person accessing this url
        if($product->getBuyer() != null and $product->getBuyer()->getId() == $user->getId()){
            $product->setBuyer(null);
            $product->setStatement("Disponible");

            if(!$cancelBookRepository->findOneBy(['user'=>$user,'product'=>$product])){
                $cancel_book = new CancelBook();
                $cancel_book->setUser($user);
                $cancel_book->setProduct($product);
                $cancelBookRepository->add($cancel_book);
            }

            foreach ($user->getConversations() as $conversation){
                if($conversation->getProduct()->getId() == $product->getId()){
                    $conversation->setUpdatedAt(new \DateTimeImmutable());
                    $conversationRepository->add($conversation);
                    $messageStepService->addMessageStep('CancelBook',$conversation);
                    return $this->redirectToRoute('app_front_conversation_show', ['uuid'=>$conversation->getUuid()], Response::HTTP_SEE_OTHER);
                }
            }
        }
    }

    #[Route('/{uuid}/report', name: 'app_front_product_report', methods: ['GET', 'POST'])]
    public function report($uuid, ProductRepository $productRepository, AlertService $alertService, NotificationService $notificationService): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $product = $productRepository->findOneBy(['uuid'=>$uuid]);
        $user = $this->getUser();
        $alertService->addAlert($user, 'Annonce', $product);
        $notificationService->addNotificationSendAlert($user);

        return $this->redirectToRoute('app_front_product_show', ['uuid'=>$product->getUuid()], Response::HTTP_SEE_OTHER);
    }

}