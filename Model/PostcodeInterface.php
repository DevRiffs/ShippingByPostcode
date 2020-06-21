<?php

namespace DevRiffs\ShippingByPostcode\Model;

interface PostcodeInterface
{

    const POSTCODE_TABLE = 'shippingbypostcode';
    const POSTCODE_ID_FIELD = 'postcode_id';
    const POSTCODE_POSTCODE_FIELD = 'postcode';
    const POSTCODE_ALLOWED_CARRIERS = 'allowed_carriers';

    const POSTCODE_FREE_SHIPPING = 1;
    const POSTCODE_NO_DELIVERY = 2;
    const POSTCODE_FLATRATE = 3;

    const SHIPPING_FREE = 'freeshipping';
    const SHIPPING_FLATRATE = 'flatrate';

    const SHIPPING_FREE_LABEL = 'Free shipping';
    const SHIPPING_FLATRATE_LABEL = 'Flatrate shipping';
    const SHIPPING_NODELIVERY_LABEL = 'No delivery';

}