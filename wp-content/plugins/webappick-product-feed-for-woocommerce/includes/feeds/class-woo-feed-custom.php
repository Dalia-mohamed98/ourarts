<?php

/**
 * Class Custom
 *
 * Responsible for processing and generating custom feed
 *
 * @since 1.0.0
 * @package Shopping
 *
 */
class Woo_Feed_Custom
{
    /**
     * This variable is responsible for holding all product attributes and their values
     *
     * @since   1.0.0
     * @var     array $products Contains all the product attributes to generate feed
     * @access  public
     */
    public $products;

    /**
     * This variable is responsible for holding feed configuration form values
     *
     * @since   1.0.0
     * @var     Custom $rules Contains feed configuration form values
     * @access  public
     */
    public $rules;


    /**
     * Store product information
     *
     * @since   1.0.0
     * @var     array $storeProducts
     * @access  public
     */
    private $storeProducts;

    /**
     * Define the core functionality to generate feed.
     *
     * Set the feed rules. Map products according to the rules and Check required attributes
     * and their values according to merchant specification.
     * @var Woo_Generate_Feed $feedRule Contain Feed Configuration
     * @since    1.0.0
     */
    public function __construct($feedRule)
    {
        $products = new Woo_Feed_Products();
        $storeProducts = $products->woo_feed_get_visible_product($feedRule);
        $engine = new WF_Engine($storeProducts, $feedRule);
        $this->products = $engine->mapProductsByRules();
        $this->rules = $feedRule;
        if ($feedRule['feedType'] == 'xml') {
            $this->mapAttributeForXML();
        }
    }


    /**
     * Prepare Feed For XML Output
     *
     */
    public function mapAttributeForXML()
    {
        $i = 0;
        $cdata = true;
        if ($this->products) {
            foreach ($this->products as $no => $product) {
                foreach ($product as $key => $value) {
                    $this->products[$no][$key] = $this->formatXMLLine($key, $value);
                }
                $i++;
            }
        }
    }

    /**
     * Format and Make the XML node for the Feed
     *
     * @param $attribute
     * @param $value
     * @param string $space
     * @return string
     */
    function formatXMLLine($attribute, $value, $space ="")
    {
        $attribute = str_replace(" ", "_", $attribute);
        //Make child node for XML
        if(!empty($value))
            $value=trim($value);
        if (strpos($value, "<![CDATA[") === false && substr(trim($value), 0, 4) == "http") {
            $value = "<![CDATA[$value]]>";
        } elseif (strpos($value, "<![CDATA[") === false && !is_numeric(trim($value)) && !empty($value)) {
            $value = "<![CDATA[$value]]>";
        }
        if($this->rules['provider'] == 'myshopping.com.au' && $attribute == "Category")
        {
            return "$space<$attribute xml:space=\"preserve\">$value</$attribute>";
        }
        return "
        $space<$attribute>$value</$attribute>";
    }

    /**
     * Return Feed
     *
     * @return array|bool|string
     */
    public function returnFinalProduct()
    {
        if(!empty($this->products)) {
            $engine = new WF_Engine($this->products, $this->rules);
            if ($this->rules['feedType'] == 'xml') {
                //return $engine->get_feed($this->products);
                $feed=array(
                    "header"=> $this->get_header($engine),
                    "body"=>$engine->get_xml_feed_body(),
                    "footer"=> $this->get_footer($engine),
                );
                return $feed;
            } elseif ($this->rules['feedType'] == 'txt') {
                //return $engine->get_txt_feed();
                $feed=array(
                    "body"=>$engine->get_txt_feed(),
                    "header"=>$engine->txtFeedHeader,
                    "footer"=>"",
                );
                return $feed;
            } elseif ($this->rules['feedType'] == 'csv') {
                //return $engine->get_csv_feed();
                $feed=array(
                    "body"=>$engine->get_csv_feed(),
                    "header"=>$engine->csvFeedHeader,
                    "footer"=>"",
                );
                return $feed;
            }
        }

        $feed=array(
            "body"=>"",
            "header"=>"",
            "footer"=>"",
        );
        return $feed;
    }

    public function get_header($engine)
    {
        $datetime_now = date("Y-m-d H:i:s");
       
        if($this->rules['provider'] == 'zap.co.il')
        {
            $zap = "<STORE>
                <datetime>$datetime_now</datetime>
                <title>". get_bloginfo('name') ."</title>
                <link>". get_bloginfo('url') ."</link>
                <description>". get_bloginfo('description') ."</description>
                <agency>". get_bloginfo('name') ."</agency>
                <email>". get_bloginfo('admin_email') ."</email>";
            return $zap;
        }
        else if($this->rules['provider'] == 'myshopping.com.au')
        {
            return "<productset>";
        }
        else if( in_array($this->rules['provider'], ['fruugo.au', 'stylight.com', 'nextad', 'skinflint.co.uk',
            'comparer.be', 'dooyoo', 'hintaseuranta.fi', 'incurvy', 'kijiji.ca', 'marktplaats.nl', 'rakuten.de',
            'shopalike.fr', 'spartoo.fi', 'webmarchand']) )
        {
            return "<products version=\"1.0\" standalone=\"yes\">
                <datetime>$datetime_now</datetime>
                <title>". get_bloginfo('name') ."</title>
                <link>". get_bloginfo('url') ."</link>
                <description>". get_bloginfo('description') ."</description>";
        }
        else if($this->rules['provider'] == "fashiola.de")
        {
            return "<products version=\"1.0\" standalone=\"yes\">
                <datetime>$datetime_now</datetime>
                <title>". get_bloginfo('name') ."</title>
                <link>". get_bloginfo('url') ."</link>
                <description>". get_bloginfo('description') ."</description>";
        }
        else if($this->rules['provider'] == 'criteo')
        {
            return "<channel>
                <title>". get_bloginfo('name') ."</title>
                <link>". get_bloginfo('url') ."</link>
                <description>". get_bloginfo('description') ."</description>";
        }
        else
        {
            return $engine->get_xml_feed_header();
        }
    }

    public function get_footer($engine)
    {
        if(in_array($this->rules['provider'], ['fruugo.au', 'stylight.com', 'nextad', 'skinflint.co.uk', 'comparer.be', 'dooyoo', 'hintaseuranta.fi', 'incurvy', 'kijiji.ca', 'marktplaats.nl', 'rakuten.de', 'shopalike.fr', 'spartoo.fi', 'webmarchand', 'fashiola.de'])) {
            return "</products>";
        }
        else if($this->rules['provider'] == 'zap.co.il')
        {
            return "</STORE>";
        }
        else if($this->rules['provider'] == 'myshopping.com.au')
        {
            return "</productset>";
        }
        else if($this->rules['provider'] == 'criteo')
        {
            return "</channel>";
        }
        else
        {
            return $engine->get_xml_feed_footer();
        }
    }
}