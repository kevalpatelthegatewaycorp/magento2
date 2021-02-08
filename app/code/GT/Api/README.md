#Introduction

-> This module provides service to make SOAP requests by using Soap Client with the help of WSDL.

#Implementation Explanation

-> The processRequest function does the work of processing the provided request with parameter and returns an array with ['raw'] and ['normalized'] keys.
-> The value for the ['raw'] array comprises of raw XML retrived from the external API, while the ['normalized'] portion comprises of XML response converted to array. 
-> The processRequest function also allows the use 'normalize' parameter (default : false) to normalize the XML response to array if it's value is set to (true). If 'normalize' parameter is set to (false) only the ['raw'] portion of response is returned. 
-> If the response returned by the external API returns array and does not contain valid XML no normalization occurs and the ['raw'] portion of array is returned.    
