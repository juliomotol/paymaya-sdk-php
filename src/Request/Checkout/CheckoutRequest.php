<?php

namespace Lloricode\Paymaya\Request\Checkout;

use Lloricode\Paymaya\Request\BaseRequest;
use Lloricode\Paymaya\Request\Checkout\Buyer\BuyerRequest;

/**
 * https://hackmd.io/@paymaya-pg/Checkout
 */
class CheckoutRequest extends BaseRequest
{
    private TotalAmountRequest $total_amount_request;
    private BuyerRequest $buyer_request;

    /**
     * @var \Lloricode\Paymaya\Request\Checkout\ItemRequest[]
     */
    private array $items = [];
    private RedirectUrlRequest $redirect_url_request;
    private ?string $request_reference_number = null;
    private MetaDataRequest $meta_data_request;

    public function setTotalAmountRequest(TotalAmountRequest $totalAmountRequest): self
    {
        $this->total_amount_request = $totalAmountRequest;

        return $this;
    }

    public function setBuyerRequest(BuyerRequest $buyerRequest): self
    {
        $this->buyer_request = $buyerRequest;

        return $this;
    }

    public function addItemRequest(ItemRequest $itemRequest): self
    {
        $this->items[] = $itemRequest;

        return $this;
    }

    public function setRedirectUrlRequest(RedirectUrlRequest $redirectUrlRequest): self
    {
        $this->redirect_url_request = $redirectUrlRequest;

        return $this;
    }

    public function setRequestReferenceNumber(?string $requestReferenceNumber): self
    {
        $this->request_reference_number = $requestReferenceNumber;

        return $this;
    }

    public function setMetaDataRequest(MetaDataRequest $metaDataRequest): self
    {
        $this->meta_data_request = $metaDataRequest;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        $items = [];

        foreach ($this->items as $itemRequest) {
            $items[] = $itemRequest->jsonSerialize();
        }

        return [
            'totalAmount' => $this->total_amount_request->jsonSerialize(),
            'buyer' => $this->buyer_request->jsonSerialize(),
            'items' => $items,
            'redirectUrl' => $this->redirect_url_request->jsonSerialize(),
            'requestReferenceNumber' => $this->request_reference_number,
            'metadata' => $this->meta_data_request->jsonSerialize(),
        ];
    }
}