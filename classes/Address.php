<?php

namespace SocialbugCRM\Classes;

class Address extends \SocialbugCRM\Classes\Data 
{
    protected $_data = array(
        'FirstName' => '',
        'LastName' => '',
        'Email' => '',
        'Company' => '',
        'CountryId' => '',
        'StateProvinceId' => '',
        'City' => '',
        'Address1' => '',
        'Address2' => '',
        'ZipPostalCode' => '',
        'PhoneNumber' => '',
        'FaxNumber' => ''
    );

    public function getFirstName()						{return $this->_data['FirstName'];}
    public function getLastName()						{return $this->_data['LastName'];}
    public function getEmail()							{return $this->_data['Email'];}
    public function getCompany()						{return $this->_data['Company'];}
    public function getCountryId()						{return $this->_data['CountryId'];}
    public function getStateProvinceId()				{return $this->_data['StateProvinceId'];}
    public function getCity()							{return $this->_data['City'];}
    public function getAddress1()						{return $this->_data['Address1'];}
    public function getAddress2()						{return $this->_data['Address2'];}
    public function getZipPostalCode()					{return $this->_data['ZipPostalCode'];}
    public function getPhoneNumber()					{return $this->_data['PhoneNumber'];}
    public function getFaxNumber()						{return $this->_data['FaxNumber'];}

    public function setFirstName($first_name)			{$this->_data['FirstName'] = (string)$first_name; return $this;}
    public function setLastName($last_name)				{$this->_data['LastName'] = (string)$last_name; return $this;}
    public function setEmail($email)					{$this->_data['Email'] = (string)$email; return $this;}
    public function setCompany($company)				{$this->_data['Company'] = (string)$company; return $this;}
    public function setCountryId($country_id)			{$this->_data['CountryId'] = (string)$country_id; return $this;}
    public function setStateProvinceId($state_id)		{$this->_data['StateProvinceId'] = (string)$state_id; return $this;}
    public function setCity($city)						{$this->_data['City'] = (string)$city; return $this;}
    public function setAddress1($address1)				{$this->_data['Address1'] = (string)$address1; return $this;}
    public function setAddress2($address2)				{$this->_data['Address2'] = (string)$address2; return $this;}
    public function setZipPostalCode($zip)				{$this->_data['ZipPostalCode'] = (string)$zip; return $this;}
    public function setPhoneNumber($phone)				{$this->_data['PhoneNumber'] = (string)$phone; return $this;}
    public function setFaxNumber($fax)					{$this->_data['FaxNumber'] = (string)$fax; return $this;}

    public function isEmpty() {
        if($this->_data['FirstName'] != '' || $this->_data['LastName'] != '') {
            return false;
        }

        return true;
    }

    public function extractFromUserBilling($user) {
        if($user->billing_first_name == '' && $user->billing_last_name == '') {
            return $this;
        }

        $this->setFirstName($user->billing_first_name);
        $this->setLastName($user->billing_last_name);
        $this->setCompany($user->billing_company);
        $this->setAddress1($user->billing_address_1);
        $this->setAddress2($user->billing_address_2);
        $this->setCity($user->billing_city);
        $this->setStateProvinceId($user->billing_state);
        $this->setZipPostalCode($user->billing_postcode);
        $this->setCountryId($user->billing_country);
        $this->setEmail($user->billing_email);
        $this->setPhoneNumber($user->billing_phone);

        return $this;
    }

    public function extractFromUserShipping($user) {
        if($user->shipping_first_name == '' && $user->shipping_last_name == '') {
            return $this;
        }

        $this->setFirstName($user->shipping_first_name);
        $this->setLastName($user->shipping_last_name);
        $this->setCompany($user->shipping_company);
        $this->setAddress1($user->shipping_address_1);
        $this->setAddress2($user->shipping_address_2);
        $this->setCity($user->shipping_city);
        $this->setStateProvinceId($user->shipping_state);
        $this->setZipPostalCode($user->shipping_postcode);
        $this->setCountryId($user->shipping_country);
        $this->setEmail($user->shipping_email);
        $this->setPhoneNumber($user->shipping_phone);

        return $this;
    }
}