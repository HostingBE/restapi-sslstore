<?php

/**
* @author Constan van Suchtelen van de Haere <constan.vansuchtelenvandehaere@hostingbe.com>
* @copyright 2024 HostingBE
*
* Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation
* files (the “Software”), to deal in the Software without restriction, including without limitation the rights to use, copy,
* modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software
* is furnished to do so, subject to the following conditions:

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
* THE SOFTWARE IS PROVIDED “AS IS”, WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
* OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS
* BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF
* OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*
*/

namespace HostingBE\Api;

class Contact {

protected $FirstName;
protected $LastName;
protected $Phone;
protected $Fax;
protected $Email;
protected $Title;
protected $OrganizationName;
protected $AddressLine1;
protected $AddressLine2;
protected $City;
protected $Region;
protected $PostalCode;
protected $Country;
protected $AddressLine3;
protected $LocalityName;

public function __construct() {
    
}

/**
 * Set Organization details
 */
public function getOrganization() {
   
    $OrganizationAddress = array(
        'AddressLine1' => $this->getAddressLine1(),
        'AddressLine2' => $this->getAddressLine2(),
        'AddressLine3' => $this->getAddressLine3(),
        'City' => $this->getCity(),
        'Region' => $this->getRegion(), 
        'PostalCode' =>  $this->getPostalCode(),
        'Country' => $this->getCountry(),
        'Phone' => $this->getPhone(),
        'Fax' => $this->getFax(), 
        'LocalityName' => $this->getLocalityName()
         );  

    return (array) $OrganizationAddress;
}

/**
 * Set Contact details
 */
public function getContact() {

$contact = array(
    'FirstName' => $this->getFirstName(), 
    'LastName' => $this->getLastName(), 
    'Phone' => $this->getPhone(), 
    'Fax' => $this->getFax(), 
    'Email' => $this->getEmail(), 
    'Title' => $this->getTitle(), 
    'OrganizationName' => $this->getOrganizationName(), 
    'AddressLine1' => $this->getAddressLine1(), 
    'AddressLine2' => $this->getAddressLine2(), 
    'City' => $this->getCity(), 
    'Region' => $this->getRegion(), 
    'PostalCode' => $this->getPostalCode(), 
    'Country' => $this->getCountry()
);
return $this;   
// return (array) $contact;
}
/**
 * 
 * set the contact details per item 
 */
public function setFirstName($FirstName) {
    $this->FirstName = $FirstName;
 }
 public function setLastName($LastName) {
     $this->LastName = $LastName;
  }
  public function setPhone($Phone)  {
      $this->Phone = $Phone; 
  }
  public function setFax($Fax) {
      $this->Fax = $Fax; 
   }
   public function setEmail($Email) {
     $this->Email = $Email; 
  }
  public function setTitle($Title) {
      $this->Title = $Title; 
   }
   public function setOrganizationName($OrganizationName) {
     $this->OrganizationName = $OrganizationName; 
  }
  public function setAddressLine1($AddressLine1) {
      $this->AddressLine1 = $AddressLine1; 
   }
   public function setAddressLine2($AddressLine2) {
      $this->AddressLine2 = $AddressLine2; 
  }
  public function setAddressLine3($AddressLine3) {
    $this->AddressLine3 = $AddressLine3;  
  }  
  public function setCity($City) {
      $this->City = $City; 
   }
   public function setRegion($Region) {
      $this->Region = $Region; 
  }
  public function setPostalCode($PostalCode) {
     $this->PostalCode = $PostalCode; 
 }
 public function setCountry($Country) {
     $this->Country = $Country; 
  }  
  public function setLocalityName($LocalityName) {
     $this->LocalityName = $LocalityName; 
  }  
/**
 * 
 * get the contact details per item 
 */
private function getFirstName() {
   return $this->FirstName; 
}
private function getLastName() {
    return $this->LastName; 
 }
 private function getPhone()  {
    return $this->Phone; 
 }
 private function getFax() {
     return $this->Fax; 
  }
  private function getEmail() {
    return $this->Email; 
 }
 private function getTitle() {
     return $this->Title; 
  }
  private function getOrganizationName() {
    return $this->OrganizationName; 
 }
 private function getAddressLine1() {
     return $this->AddressLine1; 
  }
  private function getAddressLine2() {
    return $this->AddressLine2; 
 }
 private function getAddressLine3() {
    return $this->AddressLine3; 
 }
 private function getCity() {
     return $this->City; 
  }
  private function getRegion() {
    return $this->Region; 
 }
 private function getPostalCode() {
   return $this->PostalCode; 
}
private function getCountry() {
    return $this->Country; 
 }  
 private function getLocalityName() {
    return $this->LocalityName; 
 }  
}

?>