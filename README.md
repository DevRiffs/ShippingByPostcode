### **Magento 2 module Shipping By Postcode**

This module allows you control which shipping method show to user after postcode check. 

Install using composer <br>
`composer require devriffs/shippingbypostcode`
<br>
or clone from Github
<br>
`https://github.com/DevRiffs/ShippingByPostcode`
<br>
##### Settings:<br>
In admin menu look for 'Shipping by Postcode'. There is 3 settings:
<br>
- Enable module. 
<br>Enables or disables module. Default is enabled. 
- Ship only to added postcodes. 
<br>Set Yes if you want to ship only to postcodes, added to database. If postcode is not in database, selected shipping method wont be allowed. 
<br>Set No if you want to ship to all postcodes, except those in database. Postcodes in database will not have selected shipping method.
<br> default is Yes.
<br>  
- Matching depth. 	
Select if you want exact math. You can select depth of matching. Example: if you select 'First 3 symbols', then all postcodes that match first 3 symbols will be considered as matched and rules apply.
<br> Example:
Mathing depth is 3. If you want to match postcode, which starts with 123, add postcode to database 123. Customer enters 123456 and this will be match and rule apply. If customer add 123999, it is also match.
 
 
 When adding postcode, you need to select shipping methods, that you want to apply your rules. Shipping methods are displayed all: enabled and disabled.
 
 if you have issues, questions or suggestions, please write here <br>
 `https://github.com/DevRiffs/ShippingByPostcode/issues`
  