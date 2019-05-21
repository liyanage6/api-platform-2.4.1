<?php
namespace App\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Entity\Product;
use App\Form\ProductType;
/**
 * Product controller.
 * @Route("/api", name="api_")
 */
class ProductController extends FOSRestController
{
    /**
     * Lists all Products.
     * @Rest\Get("/products")
     *
     * @return Response
     */
    public function getProductAction()
    {
        $repository = $this->getDoctrine()->getRepository(Product::class);
        $products = $repository->findall();
        return $this->handleView($this->view($products));
    }
    /**
     * Create Product.
     * @Rest\Post("/product")
     *
     * @return Response
     */
    public function postProductAction(Request $request)
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();
            return $this->handleView($this->view(['status' => 'ok'], Response::HTTP_CREATED));
        }
        return $this->handleView($this->view($form->getErrors()));
    }
}
