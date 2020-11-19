<?php declare(strict_types=1);
/**
 * @author    Igor Nikolaev <igor.sv.n@gmail.com>
 * @copyright Copyright (c) 2020, Darvin Studio
 * @link      https://www.darvin-studio.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Darvin\FileBundle\Validation\Constraints;

use Darvin\Utils\Strings\StringsUtil;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\FileValidator;

/**
 * File constraint validator
 */
class DarvinFileValidator extends FileValidator
{
    /**
     * @var array
     */
    private $options;

    /**
     * @param array $opts Options
     */
    public function __construct(array $opts)
    {
        $options = [];

        foreach ($opts as $key => $value) {
            $options[lcfirst(StringsUtil::toCamelCase($key))] = $value;
        }

        $this->options = $options;
    }

    /**
     * {@inheritDoc}
     */
    public function validate($value, Constraint $constraint): void
    {
        parent::validate($value, new File($this->options));
    }
}
