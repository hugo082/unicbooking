<?php

namespace Booking\ApiBundle\Controller;

use Booking\ApiBundle\Serializer\ProductMetadataSerializer;
use Booking\AppBundle\Entity\Metadata\Execution;
use Booking\AppBundle\Entity\Metadata\Product;
use Booking\AppBundle\Entity\Metadata\Step;
use Booking\AppBundle\Form\Metadata\ProductType;
use Booking\AppBundle\Repository\Metadata\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    /**
     * @Rest\View()
     * @Rest\Get("/product")
     */
    public function getProductsAction(Request $request)
    {
        /** @var ProductRepository $repo */
        $repo = $this->getDoctrine()->getRepository('BookingAppBundle:Metadata\Product');
        $products = $repo->getOfUser($this->getUser());

        /** @var ProductMetadataSerializer $serializer */
        $serializer = $this->get("booking.api.serializer.product.metadata");
        return $serializer->serialize($products);
    }

    /**
     * @Rest\View()
     * @Rest\Get("/product/{id}")
     */
    public function getProductAction(Request $request)
    {
        $product = $this->getDoctrine()->getRepository('BookingAppBundle:Metadata\Product')->find($request->get('id'));
        if (!$product instanceof Product)
            return new JsonResponse(['message' => 'Product not found'], Response::HTTP_NOT_FOUND);
        $userLoc = $this->getUser()->getLocation();
        if ($userLoc !== null && $product->getLocation() != $userLoc )
            return new JsonResponse(['message' => 'Product location not supported by this user'], Response::HTTP_UNAUTHORIZED);
        /** @var ProductMetadataSerializer $serializer */
        $serializer = $this->get("booking.api.serializer.product.metadata");
        return $serializer->serialize($product);
    }

    /**
     * @Rest\View()
     * @Rest\Patch("/product/{id}")
     */
    public function updateProductAction(Request $request, int $id)
    {
        $product = $this->getDoctrine()->getRepository('BookingAppBundle:Metadata\Product')->find($id);
        if (!$product instanceof Product)
            return new JsonResponse(['message' => 'Product not found'], Response::HTTP_NOT_FOUND);

        $form = $this->createForm(ProductType::class, $product, [
            ProductType::OPTION_TYPE => ProductType::TYPE_API
        ]);
        $data = json_decode($request->getContent(), true);
        $form->submit($data, false);

        $em = $this->getDoctrine()->getManager();
        $em->persist($product);
        $em->flush();

        return $this->redirectToRoute('get_product', array('id' => $id));
    }

    /**
     * @Rest\View()
     * @Rest\Post("/product/limousine/add/stop/{id}")
     */
    public function addLimousineStopAction(Request $request, int $id)
    {
        $product = $this->getDoctrine()->getRepository('BookingAppBundle:Metadata\Product')->find($id);
        if (!$product instanceof Product || !$product->getProductType()->getService()->isLimousine())
            return new JsonResponse(['message' => 'Product not found or not limousine type'], Response::HTTP_NOT_FOUND);


        if (($stop = $request->get("stop")) === null || !is_string($stop))
            return new JsonResponse([
                'message' => 'Bad request, stop not match.',
                'sended' => [
                    'request' => $request->request->all(),
                    'query' => $request->query->all()
                ],
                "founded" => $request->get("stop")
            ], Response::HTTP_BAD_REQUEST);

        $product->getLimousine()->addAdditionalStop($stop);
        $execution = $product->getExecution();
        $index = $execution->getSteps()->count() - 1;
        $execution->pushStep(Step::with($stop, $index, "icn_passenger", null, Execution::LIM_STOP_TAG));

        $em = $this->getDoctrine()->getManager();
        $em->persist($product);
        $em->flush();

        return $this->redirectToRoute('get_product', array('id' => $id));
    }
}
