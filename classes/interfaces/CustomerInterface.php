<?php

namespace SocialbugCRM\Classes\Interfaces;

/**
 * @api
 */
interface CustomerInterface {
    /**
     * Customer id
     *
     * @return int
     */
    public function getCustomerId();

    /**
     * Customer username(email)
     *
     * @return string
     */
    public function getUserName();

    /**
     * Customer email
     *
     * @return string
     */
    public function getEmail();

    /**
     * Customer shipping address
     *
     * @return \SocialbugCRM\Classes\Interfaces\AddressInterface|null
     */
    public function getShippingAddress();

    /**
     * Customer billing address
     *
     * @return \SocialbugCRM\Classes\Interfaces\AddressInterface|null
     */
    public function getBillingAddress();

    /**
     * Customer affiliate id
     *
     * @return int
     */
    public function getAffiliateId();

    /**
     * Customer password (as it was set earlier)
     *
     * @return string
     */
    public function getPassword();

    /**
     * Set the id
     *
     * @param int $id
     * @return $this
     */
    public function setCustomerId($id);

    /**
     * Set the username(email)
     *
     * @param string $username
     * @return $this
     */
    public function setUserName($username);

    /**
     * Set the email
     *
     * @param string $email
     * @return $this
     */
    public function setEmail($email);

    /**
     * Set the billing address
     *
     * @param \SocialbugCRM\Classes\Interfaces\AddressInterface|null $a
     * @return $this
     */
    public function setBillingAddress($a);

    /**
     * Set the shipping address
     *
     * @param \SocialbugCRM\Classes\Interfaces\AddressInterface|null $a
     * @return $this
     */
    public function setShippingAddress($a);

    /**
     * Set the affiliate id
     *
     * @param int $a
     * @return $this
     */
    public function setAffiliateId($a);

    /**
     * Set the customer password (for new customers only)
     *
     * @param string $p
     * @return $this
     */
    public function setPassword($p);
}