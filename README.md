CSC309 Assignment 2
===

Second assignment for CSC309

## Collaborators

* Daniel Kats (CDF ID g1dbkats) (student number 997492468)

## Assignment Specs

* See [CSC309 Website] [1]

[1]: http://www.cs.toronto.edu/~delara/courses/csc309/ "CSC309 Website"

### Desired Functionality

* Booking a flight (for customers)
* Administrating flights (for admins)

#### Booking a Flight (Customer Portal)

**index.php**

Summary:
* book one-way tickets between the UTM and St. George
* the company has 2 identical 3-seat aircraft that fly total of 8 daily flights
* flights depart from either campus at 8 AM, 10 AM, 2 PM, 5 PM

Breakdown:
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

#### Administrating Flights (admin portal)

**admin.php**

Requires the following options:
* Delete all flight and ticket data
* Populate Flight table for next 14 days
* [List all sold tickets](#sold-ticket-info)

##### Sold Ticket Info

Provide:
* flight date
* seat number
* customer first & last name
* credit card number & expiration

### General Misc

* no need to implement concurrency control


### Database Structure

Tables:
* [Campus](#campus)
* [Timetable](#timetable)
* [Flight](#flight)
* [Ticket](#ticket)

#### Campus

Fields:
* id INT <key>
* name VARCHAR (16)

About:
Has the names of the two campuses and their IDs. Creates a mapping so don't have to store strings in other DBs.
**THIS IS A STATIC TABLE**

#### Timetable

Fields:
* id INT <key>
* leavingfrom INT <foreign key, references [Campus](#campus) table>
* goingto INT <foreign key, references [Campus](#campus) table>
* time TIME

About:
Creates IDs for each possible flight in a day. Creates a mapping so can easily store info about flight with single INT.
**THIS IS A STATIC TABLE**

#### Flight

Fields:
* id INT <key>
* timetable_id INT <foreign key, references [Timetable](#timetable) table>
* date DATE
* available INT <probably a bool>

About:
This table is used to keep track of *all* available flights. Initially, should be pre-filled with all flights for 2 days. I think.
**The only thing that should change in this table is the *available* field**

#### Ticket

Fields:
* id INT <key>
* first VARCHAR (16)
* last VARCHAR (16)
* creditcardnumber VARCHAR (16)
* creditcardexpiration VARCHAR (16)
* flight_id INT <foreign key, references [Flight](#flight) table>
* seat INT

About:
A ticket for a single person. Highly dynamic, created for each ticket that is booked.

## Libraries Used

* JQuery
* JQuery UI
