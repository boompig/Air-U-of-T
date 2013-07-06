CSC309 Assignment 2
===

Second assignment for CSC309

## Collaborators

* Daniel Kats (CDF ID g1dbkats) (student number 997492468)

## Assignment Specs

* See [CSC309 Website] [1]

[1]: http://www.cs.toronto.edu/~delara/courses/csc309/ "CSC309 Website"

### Desired Functionality

* book one-way tickets between the UTM and St. George
* the company has 2 identical 3-seat aircraft that fly total of 8 daily flights
* flights depart from either campus at 8 AM, 10 AM, 2 PM, 5 PM

#### Booking a Flight

1. [Collect flight info](#flight-info)
2. [Get seat info](#seat-info)
3. [Get billing info](#billing-info)
4. [Display order summary](#summary-info)

##### Flight Info

Specify the following

* Departure campus
* Departure date

Present user with list of available flights matching search parameters that have available space.

##### Seat Info

Let user select seats by clicking on helicopter schema (see [CSC309 Website] [1])

Color Scheme:

* Available seats are white
* Occupied seats are yellow
* Customer's current seat selection is green. 

##### Billing Info

Collect the following:

* First and last name
* Credit card number
* Credit card expiration date

Enforce the following validation rules:

* No fields can be empty
* Credit card number must have 16 digits
* Expiration date should be a valid date (MM/YY)
* The card should not have expired

##### Summary Info

* Display customer's flight information
* Provide a way to print the page

##### Simplifying Assumptions

* Tickets cannot be bought for the current date
* Tickets can be bough at most 2 weeks in advance
* Only 1 ticket can be bought at a time
* All tickets cost $20 

