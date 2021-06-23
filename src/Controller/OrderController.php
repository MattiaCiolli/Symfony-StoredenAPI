<?php
namespace App\Controller;
use Storeden\Storeden;
use App\Entity\Order;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class OrderController extends AbstractController
{
    public function createOrderDB(): Response
    {
		$_settings = array(
			'api_key'=>'key',
			'api_exchange'=>'exc',
			'api_version'=> 'v1.1'
		);
		$api=new Storeden($_settings);
		$response=$api->get('/orders/list.json');
        //$number = random_int(0, 100);
		$orders = json_decode($response->getBody());
		$entityManager = $this->getDoctrine()->getManager();

        foreach ($orders as $o)
        {
            $order = new Order();
            $order->setOrderID($o->orderID);
            $order->setTotal($o->total);
            $order->setOrderDate($o->orderDate);
            $order->setStatus($o->status);

            $entityManager->persist($order);

            $entityManager->flush();
        }

        echo '<script language="javascript">';
		echo 'alert("Orders loaded")';
		echo '</script>';

		return $this->render('create.html.twig');
    }

    public function showOrders(): Response
    {
        $orders = $this->getDoctrine()
            ->getRepository(Order::class)
            ->findAll();

        return $this->render('orders.html.twig', [
            'orders' => $orders
        ]);
    }

	public function searchOrder(Request $request): Response
    {
		$searchStr = $request->query->get('searchStr');
        $entityManager = $this->getDoctrine();

        $result = $entityManager->getRepository(Order::class)->createQueryBuilder('o')
        ->where('o.orderID LIKE :search')
        ->setParameter('search', '%'.$searchStr.'%')
        ->getQuery()
        ->getResult();


        return $this->render('orders.html.twig', [
            'orders' => $result
        ]);
    }

    public function orderTotal(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT o.orderID AS id, o.total AS total
            FROM App\Entity\Order AS o'
        );
        $result = $query->getArrayResult();

        $response = new Response(json_encode($result));
        $response->headers->set('Content-Type', 'application/json');
        
        return $response;
    }

    public function orderCount(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT COUNT(o.orderID) AS countOrder
            FROM App\Entity\Order AS o'
        );
        $result = $query->getArrayResult();

        $response = new Response(json_encode($result));
        $response->headers->set('Content-Type', 'application/json');
        
        return $response;
    }

    public function orders2csv()
    {
		$orders = $this->getDoctrine()
            ->getRepository(Order::class)
            ->findAll();

        $content;
        if(count($orders)) {
          /* salva in locale
          $csvPath = './orders.csv';

           $csvh = fopen($csvPath, 'w');
           $d = ','; 
           $e = '"';

           foreach($orders as $o) {
               $data = (array)$o; // false for the shallow conversion
               fputcsv($csvh, $data, $d, $e);
           }

           fclose($csvh);
           echo '<script language="javascript">';
           echo 'alert("Orders saved to csv")';
           echo '</script>';*/
           $rows = array();
            foreach ($orders as $o) {
                $data = array($o->getorderID(), $o->getTotal(), $o->getOrderDate(), $o->getStatus());
            
                $rows[] = implode(',', $data);
            }
        
            $content = implode("\n", $rows);
        }
        else
        {
            echo '<script language="javascript">';
            echo 'alert("No order to be saved to csv")';
            echo '</script>';
        }

        $response = new Response($content);
        $response->headers->set('Content-Type', 'text/csv');

        return $response;
    }

    public function saveOrderStatus(Request $request): Response
    {
		$newStatus = $request->query->get('newStatus');
        $oid = $request->query->get('oid');
        $entityManager = $this->getDoctrine()->getManager();
        $order = $entityManager->getRepository(Order::class)->find($oid);

        if (!$order) {
            throw $this->createNotFoundException(
                'No order found for id '.$id
            );
        }

        $order->setStatus($newStatus);
        $entityManager->flush();
   
        $response = $this->forward('App\Controller\OrderController::showOrders');
	
		return $response;
    }

    public function deleteOrder(Request $request): Response
    {
        $oid = $request->query->get('oid');
        $entityManager = $this->getDoctrine()->getManager();
        $order = $entityManager->getRepository(Order::class)->find($oid);

        if (!$order) {
            throw $this->createNotFoundException(
                'No order found for id '.$oid
            );
        }

        $entityManager->remove($order);

        $entityManager->flush();

        echo '<script language="javascript">';
        echo 'alert("Order deleted")';
        echo '</script>';
   
        $response = $this->forward('App\Controller\OrderController::showOrders');
	
		return $response;
    }
}