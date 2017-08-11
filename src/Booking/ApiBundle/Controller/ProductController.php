<?php

namespace Booking\ApiBundle\Controller;

use Booking\ApiBundle\Serializer\ProductMetadataSerializer;
use Booking\AppBundle\Entity\Metadata\Product;
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
}
