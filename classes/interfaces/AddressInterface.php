<?php

namespace SocialbugCRM\Classes\Interfaces;

/**
 * @api
 */
interface AddressInterface {

    /**
     * Address First Name
     *
     * @return string
     */
    public function getFirstName();

    /**
     * Address Last Name
     *
     * @return string
     */
    public function getLastName();

    /**
     * Address email (the customer email)
     *
     * @return string
     */
    public function getEmail();

    /**
     * Address Company
     *
     * @return string
     */
    public function getCompany();

    /**
     * Address Country Id
     *
     * @return string
     */
    public function getCountryId();

    /**
     * Address County/State/Province Id
     *
     * @return int
     */
    public function getStateProvinceId();

    /**
     * Address City
     *
     * @return string
     */
    public function getCity();

    /**
     * Address Street Line 1
     *
     * @return string
     */
    public function getAddress1();

    /**
     * Address Street Line 2
     *
     * @return string
     */
    public function getAddress2();

    /**
     * Address Zip Postal Code
     *
     * @return string
     */
    public function getZipPostalCode();

    /**
     * Address Phone Number
     *
     * @return string
     */
    public function getPhoneNumber();

    /**
     * Address Fax Number
     *
     * @return string
     */
    public function getFaxNumber();

    /**
     * Set the Address First Name
     *
     * @param string $first_name
     * @return $this
     */
    public function setFirstName($first_name);

    /**
     * Set the Address Last Name
     *
     * @param string $last_name
     * @return $this
     */

    public function setLastName($last_name);

    /**
     * Set the Address Email
     *
     * @param string $email
     * @return $this
     */
    public function setEmail($email);

    /**
     * Set the Company Name
     *
     * @param string $company
     * @return $this
     */
    public function setCompany($company);

    /**
     * Set the Country Id
     *
     * @param string $country_id
     * @return $this
     */
    public function setCountryId($country_id);

    /**
     * Set the County/State/Province Id
     *
     * @param int $state_id
     * @return $this
     */
    public function setStateProvinceId($state_id);

    /**
     * Set the Address City
     *
     * @param string $city
     * @return $this
     */
    public function setCity($city);

    /**
     * Set the Address Line 1
     *
     * @param string $address_1
     * @return $this
     */
    public function setAddress1($address_1);

    /**
     * Set the Address Line 2
     *
     * @param string $address_2
     * @return $this
     */
    public function setAddress2($address_2);

    /**
     * Set the Address Zip/Postal Code
     *
     * @param string $zip
     * @return $this
     */
    public function setZipPostalCode($zip);

    /**
     * Set the Address Phone Number
     *
     * @param string $phone
     * @return $this
     */
    public function setPhoneNumber($phone);

    /**
     * Set the Address Fax Number
     *
     * @param string $fax
     * @return $this
     */
    public function setFaxNumber($fax);
}