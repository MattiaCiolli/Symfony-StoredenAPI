<?php
namespace App\Controller;
use Storeden\Storeden;
use App\Entity\Product;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\FileUploader;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class ProductController extends AbstractController
{
    public function createProductDB() : Response
    {
		$_settings = array(
			'api_key'=>'key',
			'api_exchange'=>'exc',
			'api_version'=> 'v1.1'
		);
		$api=new Storeden($_settings);
		$response=$api->get('/products/list.json?nodes=title,image_id');
        //$number = random_int(0, 100);
		$products = json_decode($response->getBody());
		$entityManager = $this->getDoctrine()->getManager();
        foreach ($products as $p)
        {
            $product = new Product();
            $product->setUid($p->uid);
            $product->setCode($p->code);
            $product->setPrice($p->price);
			$product->setFinalPrice($p->final_price);
            $product->setStatus($p->status);
            $product->setTitle($p->title);
            if(property_exists($p, 'image_id'))
            {
            $product->setImageId($p->image_id);
            }
            else{
                $product->setImageId("no image");
            }

            $entityManager->persist($product);

            $entityManager->flush();
        }

		echo '<script language="javascript">';
		echo 'alert("Products loaded")';
		echo '</script>';

		return $this->render('create.html.twig');
    }

	public function showProducts(): Response
    {
        $products = $this->getDoctrine()
            ->getRepository(Product::class)
            ->findBy(
                array(),
                array(),
                30,
                0
            );

        return $this->render('products.html.twig', [
            'products' => $products
        ]);
    }

    public function tableProducts(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT p
            FROM App\Entity\Product p'
        );

        $products = $query->getArrayResult();

        $response = new Response(json_encode($products));
        $response->headers->set('Content-Type', 'application/json');
        
        return $response;
    }

    public function productCount(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT COUNT(p.uid) AS countProduct
            FROM App\Entity\Product AS p'
        );
        $result = $query->getArrayResult();

        $response = new Response(json_encode($result));
        $response->headers->set('Content-Type', 'application/json');
        
        return $response;
    }

    public function searchProduct(Request $request): Response
    {
		$searchStr = $request->query->get('searchStr');
        $entityManager = $this->getDoctrine();

        $result = $entityManager->getRepository(Product::class)->createQueryBuilder('p')
        ->where('p.code LIKE :search')
        ->setParameter('search', '%'.$searchStr.'%')
        ->getQuery()
        ->getResult();


        return $this->render('products.html.twig', [
            'products' => $result
        ]);
    }

    public function products2csv()
    {
		$products = $this->getDoctrine()
            ->getRepository(Product::class)
            ->findAll();

        $content;
        if(count($products)) {

           $rows = array();
            foreach ($products as $p) {
                $data = array($p->getUid(), $p->getTitle(), $p->getCode(), $p->getPrice(), $p->getFinalPrice(), $p->getStatus(), $p->getImageId());
            
                $rows[] = implode(',', $data);
            }
        
            $content = implode("\n", $rows);
        }
        else
        {
            echo '<script language="javascript">';
            echo 'alert("No product to be saved to csv")';
            echo '</script>';
        }

        $response = new Response($content);
        $response->headers->set('Content-Type', 'text/csv');

        return $response;
    }

    public function saveProductName(Request $request): Response
    {
		$newName = $request->query->get('newName');
        $pid = $request->query->get('pid');
        $entityManager = $this->getDoctrine()->getManager();
        $product = $entityManager->getRepository(Product::class)->find($pid);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        $product->setTitle($newName);
        $entityManager->flush();
   
        $response = $this->forward('App\Controller\ProductController::showProducts');
	
		return $response;
    }

    public function deleteProduct(Request $request): Response
    {
        $pid = $request->query->get('pid');
        $entityManager = $this->getDoctrine()->getManager();
        $product = $entityManager->getRepository(Product::class)->find($pid);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$pid
            );
        }

        $file2remove = $product->getImageId();
        if(file_exists($file2remove)){
            if (!unlink($file2remove)) { 
                echo ("file cannot be deleted due to an error"); 
            } 
            else { 
                echo ("file has been deleted"); 
            } 
        }else{
            echo ('file not found');
        }
        

        $entityManager->remove($product);

        $entityManager->flush();
   
        echo '<script language="javascript">';
        echo 'alert("Product deleted")';
        echo '</script>';

        $response = $this->forward('App\Controller\ProductController::showProducts');
	
		return $response;
    }

    public function saveProductImage(Request $request, FileUploader $uploader): Response
    {
        $pid = $request->get('pid');
		$newImage = $request->files->get('newImage');
        $entityManager = $this->getDoctrine()->getManager();
        $product = $entityManager->getRepository(Product::class)->find($pid);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$pid
            );
        }

        if (empty($newImage))
        {
            return new Response("No file specified",
               Response::HTTP_UNPROCESSABLE_ENTITY, ['content-type' => 'text/plain']);
        }

         $filename =$uploader->upload($newImage);

        $product->setImageId("uploads/".$filename);
        $entityManager->flush();
   
        echo '<script language="javascript">';
        echo 'alert("File uploaded")';
        echo '</script>';

        $response = $this->forward('App\Controller\ProductController::showProducts');
	
		return $response;
    }
}