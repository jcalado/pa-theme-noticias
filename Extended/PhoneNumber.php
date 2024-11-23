<?php

namespace ExtendedLocal;

use Extended\ACF\Fields\Field;
use Extended\ACF\ConditionalLogic;
use Extended\ACF\Fields\Settings\Instructions;
use Extended\ACF\Fields\Settings\Required;
use Extended\ACF\Fields\Settings\Wrapper;
/**
 * Register new Phone Number field
 */
class PhoneNumber extends Field {


    use Instructions;
    use Wrapper;
    use Required;

    protected null|string $type = 'phone_number';

}
