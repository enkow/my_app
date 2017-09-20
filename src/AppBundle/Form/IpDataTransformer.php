<?php
/**
 * Ip data transformer.
 */
namespace AppBundle\Form;

use Symfony\Component\Form\DataTransformerInterface;

/**
 * Class IpDataTransformer.
 *
 * @package AppBundle\Form
 */
class IpDataTransformer implements DataTransformerInterface
{
    /**
     * Transform array of ip to string of names.
     *
     * @param array $ip Ip entity array
     *
     * @return string Result
     */
    public function transform($ip)
    {
        if (!$ip) {
            return '';
        }

        $ips = json_decode($ip);

        return implode("\n", $ips);
    }

    /**
     * Transform string of tag names into array of Tag entities.
     *
     * @param string $string String of tag names
     *
     * @return array Result
     */
    public function reverseTransform($string)
    {
        $results = explode("\n", $string);

        $ips = [];
        foreach ($results as $ip) {
            if (trim($ip) !== '') {
                $ips[] = trim($ip);
            }
        }

        return json_encode($ips);
    }
}
