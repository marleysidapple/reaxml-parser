<?php namespace Marleysid\Reaxml;

use File;

class Reaxml
{

    protected $filePath;

    /*
     * Setting application key
     *
     */

    public function __construct()
    {
        //   $this->filePath = $filePath;
    }

    /*
     * Get the Lat lng from provided address by user
     * Successful result will return Latitude and longitude for the given place
     *
     */
    public function parseXml($filePath)
    {
        if (File::files($filePath) == "") {
            throw new Exception("Directory is empty");
        }

        foreach (File::files($filePath) as $key => $val) {

            $parsedData                           = simplexml_load_file($val);
            $propertyData[$key]['mode']           = $this->propertyMode($parsedData);
            $propertyData[$key]['status']         = $this->propertyStatus($parsedData);
            $propertyData[$key]['price']          = $this->propertyPrice($parsedData);
            $propertyData[$key]['agentDetail']    = $this->agentDetail($parsedData);
            $propertyData[$key]['address']        = $this->propertyLocation($parsedData);
            $propertyData[$key]['category']       = $this->propertyCategory($parsedData);
            $propertyData[$key]['features']       = $this->propertyFeature($parsedData);
            $propertyData[$key]['headline']       = $this->propertyHeadline($parsedData);
            $propertyData[$key]['description']    = $this->propertyDescription($parsedData);
            $propertyData[$key]['allowances']     = $this->propertyAllowance($parsedData);
            $propertyData[$key]['ecofriendly']    = $this->propertyEcoFriendly($parsedData);
            $propertyData[$key]['inspectiontime'] = $this->propertyInspectionTime($parsedData);
            $propertyData[$key]['externalLink']   = $this->propertyExternalLink($parsedData);
            $propertyData[$key]['videoLink']      = $this->propertyVideoLink($parsedData);
            $propertyData[$key]['images']         = $this->propertyImages($parsedData);

        }

        return $propertyData;

    }

    /*
     * returns the current status of the property
     *
     * It can be either rental, residential
     * @returns rental, residential
     */

    public function propertyMode($xmlData)
    {
        if (isset($xmlData->rental)) {
            $mode = 'rental';
        } elseif (isset($xmlData->residential)) {
            $mode = 'residential';
        } else {
            throw new \Exception("Invalid xml file. Node donot exists");
        }

        return $mode;
    }

    /*
     * returns the current status of the property
     *
     * It can be either sold or current
     * @returns current, withdrawn, offmarket or sold
     */

    public function propertyStatus($xmlData)
    {
        if (isset($xmlData->rental) || isset($xmlData->residential)) {
            $status = $xmlData->{$this->propertyMode($xmlData)}['status'];
        } else {
            throw new \Exception("Invalid xml file. Status node donot exists");
        }
        return (string) $status;
    }

    /*
     *
     * returns property price
     *
     *
     */
    public function propertyPrice($xmlData)
    {
        if (isset($xmlData->rental) && isset($xmlData->rental->rent)) {
            $price = $xmlData->rental->priceView;
        } elseif (isset($xmlData->residential) && isset($xmlData->residential->price)) {
            $price = $xmlData->residential->price;
        } else {
            throw new \Exception("Invalid xml file. price node donot exists");
        }
        return (string) $price;
    }

    /*
     *
     *
     * getting property listing agent details
     *
     */
    public function agentDetail($xmlData)
    {
        if (isset($xmlData->rental) && isset($xmlData->rental->listingAgent) || isset($xmlData->residential) && isset($xmlData->residential->listingAgent)) {
            $agentDetail['name']      = (string) $xmlData->{$this->propertyMode($xmlData)}->listingAgent->name;
            $agentDetail['telephone'] = (string) $xmlData->{$this->propertyMode($xmlData)}->listingAgent->telephone;
            $agentDetail['email']     = (string) $xmlData->{$this->propertyMode($xmlData)}->listingAgent->email;
        } else {
            throw new \Exception("Invalid xml file. Agent node donot exists");
        }
        return $agentDetail;
    }

    /*
     *
     * getting property address
     *
     */
    public function propertyLocation($xmlData)
    {

        if (isset($xmlData->rental) && isset($xmlData->rental->address) || isset($xmlData->residential) && isset($xmlData->residential->address)) {

            $location['streetNumber'] = (string) $xmlData->{$this->propertyMode($xmlData)}->address->streetNumber;
            $location['street']       = (string) $xmlData->{$this->propertyMode($xmlData)}->address->street;
            $location['suburb']       = (string) $xmlData->{$this->propertyMode($xmlData)}->address->suburb;
            $location['state']        = (string) $xmlData->{$this->propertyMode($xmlData)}->address->state;
            $location['postcode']     = (string) $xmlData->{$this->propertyMode($xmlData)}->address->postcode;
            $location['country']      = (string) $xmlData->{$this->propertyMode($xmlData)}->address->country;

        } else {
            throw new \Exception("Invalid xml file. Location node donot exists");
        }
        return $location;

    }

    /*
     *
     * getting the category of the property
     *
     */
    public function propertyCategory($xmlData)
    {
        if (isset($xmlData->rental) && isset($xmlData->rental->category) || isset($xmlData->residential) && isset($xmlData->residential->category)) {
            $category = $xmlData->{$this->propertyMode($xmlData)}->category->attributes()->name;
        } else {
            throw new \Exception("Invalid xml file. Category node donot exists");
        }
        return (string) $category;
    }

    /*
     *
     *
     * getting features of the property
     *
     */
    public function propertyFeature($xmlData)
    {
        if (isset($xmlData->rental) && isset($xmlData->rental->features) || isset($xmlData->residential) && isset($xmlData->residential->features)) {

            $features['openSpaces']      = (string) $xmlData->{$this->propertyMode($xmlData)}->features->openSpaces;
            $features['bedrooms']        = (string) $xmlData->{$this->propertyMode($xmlData)}->features->bedrooms;
            $features['bathrooms']       = (string) $xmlData->{$this->propertyMode($xmlData)}->features->bathrooms;
            $features['garages']         = (string) $xmlData->{$this->propertyMode($xmlData)}->features->garages;
            $features['carports']        = (string) $xmlData->{$this->propertyMode($xmlData)}->features->carports;
            $features['airConditioning'] = (string) $xmlData->{$this->propertyMode($xmlData)}->features->airConditioning;
            $features['builtInRobes']    = (string) $xmlData->{$this->propertyMode($xmlData)}->features->builtInRobes;
            $features['alarmSystem']     = (string) $xmlData->{$this->propertyMode($xmlData)}->features->alarmSystem;
            $features['tennisCourt']     = (string) $xmlData->{$this->propertyMode($xmlData)}->features->tennisCourt;
            $features['intercom']        = (string) $xmlData->{$this->propertyMode($xmlData)}->features->intercom;
            $features['openFirePlace']   = (string) $xmlData->{$this->propertyMode($xmlData)}->features->openFirePlace;
            $features['vacuumSystem']    = (string) $xmlData->{$this->propertyMode($xmlData)}->features->vacuumSystem;
            $features['ensuite']         = (string) $xmlData->{$this->propertyMode($xmlData)}->features->ensuite;
            $features['floorboards']     = (string) $xmlData->{$this->propertyMode($xmlData)}->features->floorboards;
            $features['gym']             = (string) $xmlData->{$this->propertyMode($xmlData)}->features->gym;
            $features['study']           = (string) $xmlData->{$this->propertyMode($xmlData)}->features->study;
            $features['courtyard']       = (string) $xmlData->{$this->propertyMode($xmlData)}->features->courtyard;
            $features['fullyFenced']     = (string) $xmlData->{$this->propertyMode($xmlData)}->features->fullyFenced;
            $features['secureParking']   = (string) $xmlData->{$this->propertyMode($xmlData)}->features->secureParking;

        } else {
            throw new \Exception("Invalid xml file. Feature node donot exists");
        }
        return $features;
    }

    /*
     *
     * getting property headline
     *
     */
    public function propertyHeadline($xmlData)
    {
        if (isset($xmlData->rental) && isset($xmlData->rental->headline) || isset($xmlData->residential) && isset($xmlData->residential->headline)) {
            $headline = $xmlData->{$this->propertyMode($xmlData)}->headline;
        } else {
            throw new \Exception("Invalid xml file. headline node donot exists");
        }
        return (string) $headline;
    }

    /*
     *
     * getting property description
     *
     */
    public function propertyDescription($xmlData)
    {
        if (isset($xmlData->rental) && isset($xmlData->rental->description) || isset($xmlData->residential) && isset($xmlData->residential->description)) {
            $description = preg_replace('/\n\n+/', '\n\n', $xmlData->{$this->propertyMode($xmlData)}->description);
        } else {
            throw new \Exception("Invalid xml file. description node donot exists");
        }
        return (string) $description;
    }

    /*
     *
     * getting property allowances
     * returns either 0 or 1
     * 0 false, 1 true
     */
    public function propertyAllowance($xmlData)
    {
        if ((isset($xmlData->rental) && isset($xmlData->rental->allowances)) || (isset($xmlData->residential) && isset($xmlData->residential->allowances))) {
            $allowances['petFriendly'] = (string) $xmlData->{$this->propertyMode($xmlData)}->allowances->petFriendly;
            $allowances['furnished']   = (string) $xmlData->{$this->propertyMode($xmlData)}->allowances->furnished;
            $allowances['smokers']     = (string) $xmlData->{$this->propertyMode($xmlData)}->allowances->smokers;
        } elseif (!isset($xmlData->rental->allowances) || !isset($xmlData->residential->allowances)) {
            return;
        } else {
            throw new \Exception("Invalid xml file. allowance node donot exists");
        }

        return $allowances;

    }

    /*
     *
     * getting ecofriendly data
     * returns either 0 or 1
     * 0 false, 1 true
     */
    public function propertyEcoFriendly($xmlData)
    {
        if ((isset($xmlData->rental) && isset($xmlData->rental->ecoFriendly)) || (isset($xmlData->residential) && isset($xmlData->residential->ecoFriendly))) {
            $ecoFriendly['greyWaterSystem'] = (string) $xmlData->{$this->propertyMode($xmlData)}->ecoFriendly->greyWaterSystem;
            $ecoFriendly['solarHotWater']   = (string) $xmlData->{$this->propertyMode($xmlData)}->ecoFriendly->solarHotWater;
            $ecoFriendly['solarPanels']     = (string) $xmlData->{$this->propertyMode($xmlData)}->ecoFriendly->solarPanels;
            $ecoFriendly['waterTank']       = (string) $xmlData->{$this->propertyMode($xmlData)}->ecoFriendly->waterTank;

        } elseif (!isset($xmlData->rental->ecoFriendly) || !isset($xmlData->residential->ecoFriendly)) {
            return;
        } else {
            throw new \Exception("Invalid xml file. ecoFriendly node donot exists");
        }

        return $ecoFriendly;

    }

    /*
     *
     * getting inspection time of property
     * @returns single date string or array()
     * Inspection times can be multiple
     */
    public function propertyInspectionTime($xmlData)
    {
        if ((isset($xmlData->rental) && isset($xmlData->rental->inspectionTimes)) || (isset($xmlData->residential) && isset($xmlData->residential->inspectionTimes))) {
            $inspectionTimes = $xmlData->{$this->propertyMode($xmlData)}->inspectionTimes->inspection;
        } elseif (!isset($xmlData->rental->inspectionTimes) || !isset($xmlData->residential->inspectionTimes)) {
            return;
        } else {
            throw new \Exception("Invalid xml file. inspectionTimes node donot exists");
        }

        return $inspectionTimes;
    }

    /*
     *
     *
     * getting external link for the property
     *
     */
    public function propertyExternalLink($xmlData)
    {
        if (isset($xmlData->rental) && isset($xmlData->rental->externalLink) || isset($xmlData->residential) && isset($xmlData->residential->externalLink)) {
            $externalLink = $xmlData->{$this->propertyMode($xmlData)}->externalLink->attributes()->href;
        } else {
            throw new \Exception("Invalid xml file. externalLink node donot exists");
        }
        return (string) $externalLink;
    }

    /*
     *
     *
     * getting video link for the property
     *
     */
    public function propertyVideoLink($xmlData)
    {
        if (isset($xmlData->rental) && isset($xmlData->rental->videoLink) || isset($xmlData->residential) && isset($xmlData->residential->videoLink)) {
            $videoLink = $xmlData->{$this->propertyMode($xmlData)}->videoLink->attributes()->href;
        } else {
            throw new \Exception("Invalid xml file. videoLink node donot exists");
        }
        return (string) $videoLink;
    }

    /*
     *
     *
     * Getting property images
     *
     */
    public function propertyImages($xmlData)
    {
       /* if (isset($xmlData->rental) && isset($xmlData->rental->objects) || isset($xmlData->residential) && isset($xmlData->residential->objects)) {
            //  $images = $xmlData->{$this->propertyMode($xmlData)}->objects->img[1]->attributes()->id;
            $images = $xmlData->{$this->propertyMode($xmlData)}->objects->img;
            // print_r($images->img);
            $image = array();
            foreach ($images as $key => $value) {
                

                  $image[$value->attributes()->id]$value->attributes()->url;
                // print_r($value);
                //   $image[] =  $value->attributes()->url;
                // $image['id'] = $value->img[$key]->attributes()->id;
            }
        } else {
            throw new \Exception("Invalid xml file. Images node donot exists");
        }
        print_r($image);
        die();
        // return $image;*/
    }

}
