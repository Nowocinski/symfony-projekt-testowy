<?php
    namespace App\Controller;

    use App\Entity\Produkt;
    use App\Entity\Kategoria;

    use App\DTO\ProduktDTOremove;
    use App\DTO\ProduktDTOupdate;
    use App\DTO\ProduktDTOcreate;

    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\Request;

    use Symfony\Component\Routing\Annotation\Route;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;

    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\AcceptHeader;

    // Dekodowanie json do objektu
    use Symfony\Component\Serializer\Encoder\JsonEncoder;
    use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
    use Symfony\Component\Serializer\Serializer;

    // Logger
    use Psr\Log\LoggerInterface;

    class ProduktController extends Controller {
        private $logger;
        private $serializer;

        public function __construct(LoggerInterface $logger)
        {
            $this->logger = $logger;

            $encoders = [new JsonEncoder()];
            $normalizers = [new ObjectNormalizer()];
            $this->serializer = new Serializer($normalizers, $encoders);
        }

        /**
         * @Route("/example-html")
         * @Method({"GET"})
         */
        public function html() {
            return new Response('<html><body>Hello!</body></html>');
        }

        /**
         * @Route("/example-object")
         * @Method({"GET"})
         */
        public function get_object() {
            $response = new Response();
            $response->setContent(json_encode([
                'data' => 123,
                'string' => 'test',
                'tablica' => ['test', 'test2']
            ]));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }

        /**
         * @Route("/produkt", methods={"GET"})
         */
        public function get_doctrine() {

            $produkt = $this->getDoctrine()->getRepository(Produkt::class)->findAll();

            $serializer = $this->container->get('jms_serializer');
            $response = $serializer->serialize($produkt, 'json');

            return new Response($response, 200, array('Content-Type' => 'application/json'));
        }

        /**
         * @Route("/produkt/{id}", methods={"GET"})
         */
        public function get_element_doctrine($id) {

            $produkt = $this->getDoctrine()->getRepository(Produkt::class)
                ->findOneBy(['id' => $id]);

            $serializer = $this->container->get('jms_serializer');
            $response = $serializer->serialize($produkt, 'json');

            return new Response($response, 200, array('Content-Type' => 'application/json'));
        }

        /**
         * @Route("/produkt", methods={"POST"})
         */
        public function post_doctrine(Request $request) {

            $dto = $this->serializer->deserialize($request->getContent(), ProduktDTOcreate::class, 'json');

            if ($dto->nazwa == null || $dto->cena == null) {
                return new JsonResponse('Niepoprawne dane', JsonResponse::HTTP_NOT_FOUND);
            }

            $kategoria = $this->getDoctrine()->getRepository(Kategoria::class)
                ->findOneBy(['nazwa' => $dto->kategoria]);

            if ($kategoria == null) {
                return new JsonResponse('Kategoria nie istnieje', JsonResponse::HTTP_NOT_FOUND);
            }

            $produkt = new Produkt();
            $produkt->setNazwa($dto->nazwa);
            $produkt->setCena($dto->cena);
            $produkt->setKategoria($kategoria);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($produkt);
            $entityManager->flush();

            return new JsonResponse(['status' => 'ok'], JsonResponse::HTTP_CREATED);
        }

        /**
         * @Route("/produkt", methods={"DELETE"})
         */
        public function delete(Request $request) {
            $dto = $this->serializer->deserialize($request->getContent(), ProduktDTOremove::class, 'json');

            // https://symfony.com/doc/current/doctrine.html
            $doUsuniecia = $this->getDoctrine()->getRepository(Produkt::class)
                ->findOneBy(['id' => $dto->id]);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($doUsuniecia);
            $entityManager->flush();

            return new JsonResponse(['status' => 'ok'], JsonResponse::HTTP_NO_CONTENT);
        }

        /**
         * @Route("/produkt", methods={"PUT"})
         */
        public function update(Request $request) {
            $dto = $this->serializer->deserialize($request->getContent(), ProduktDTOupdate::class, 'json');

            $doEdycji = $this->getDoctrine()->getRepository(Produkt::class)
                ->findOneBy(['id' => $dto->id]);

            if ($doEdycji == null) {
                return new JsonResponse(['status' => 'not found'], JsonResponse::HTTP_NOT_FOUND);
            }

            $doEdycji->setTekst($dto->tekst);
            $doEdycji->setData($dto->data);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return new JsonResponse(['status' => 'ok'], JsonResponse::HTTP_NO_CONTENT);
        }
    }

    /*
    
        symfony new nazwaprojektu - tworzenie nowego projektu
        php bin/console server:start - uruchomienie serwera

        ################################################
        Łączenie się z bazą danych (MySQL)

        1. W konsoli
            composer require symfony/orm-pack
            composer require --dev symfony/maker-bundle
            
            lub
            
            composer require doctrine maker
            
        2. W pliku .env ustawiamy parametry połączenia z bazą danych
            DATABASE_URL=mysql:hasło//nazwaużytkownika:@127.0.0.1:3306/nazwabazy

        3. Tworzenie bazy danych - w konsoli
            php bin/console doctrine:database:create
            
        4. Tworzenie klasy encji - w konsoli
            php bin/console make:entity nazwaencji

        5. Modyfikacja pliku encji

        6. Tworzenie migracji - w konsoli
            php bin/console doctrine:migrations:diff

        7. Wysłanie migracji
            php bin/console doctrine:migrations:migrate
            
        ################################################
            GET
            - Serializacja doctrine do json

        1. 	 W konsoli
            composer require jms/serializer-bundle

        2. 	 W kontrolerze
            
            $serializer = $this->container->get('jms_serializer');
            $reports = $serializer->serialize($doctrineobject, 'json');
            return new Response($response, 200, array('Content-Type' => 'application/json'));
            
        ################################################
            POST

        1. Odnośniki
            use Symfony\Component\Serializer\Encoder\JsonEncoder;
            use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
            use Symfony\Component\Serializer\Serializer;

        2. W metodzie docelowej
            $encoders = [new JsonEncoder()];
                    $normalizers = [new ObjectNormalizer()];
                    $serializer = new Serializer($normalizers, $encoders);
                    $obiektKlasy = $serializer->deserialize($request->getContent(), NazwaKlasy::class, 'json');
        ################################################
            Tworzenie statusów
            
            return new JsonResponse(
                        [
                            'status' => 'ok',
                        ],
                        JsonResponse::HTTP_CREATED
                    );

    */
?>