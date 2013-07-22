CSC309 Assignment 2
===

Second assignment for CSC309

## Collaborators

* Daniel Kats (CDF ID g1dbkats) (student number 997492468)
* Jacky Fong (CDF ID c8fongkw) (student number 991686118)
## Assignment Specs

* See [CSC309 Website] [1]

[1]: http://www.cs.toronto.edu/~delara/courses/csc309/ "CSC309 Website"

### Desired Functionality

* [Booking a flight](#customer-portal)
* [Administrating flights](#admin-portal)

#### Customer Portal

This is the gateway for booking flights. 

Summary:

* book one-way tickets between the UTM and St. George
* the company has 2 identical 3-seat aircraft that fly total of 8 daily flights
* flights depart from either campus at 8 AM, 10 AM, 2 PM, 5 PM

Breakdown:

1. [Collect flight info](#landing-page)

2. [Choose a flight](#flight-info)

3. [Choose a set](#seat-info)

4. [Get billing info](#billing-info)

5. [Display order summary](#summary-info)


##### Landing Page

URL: `landing.php`

MODEL: `airuoft.php`

Model:

* retrieve all flights which are available for the given campus and date

Collect the following info:

* Departure campus
* Departure date

When user selects these parameters:

* retrieve matching flight times
* redirect to [Flight Info](#flight-info)

##### Flight Info

URL: `flightinfo.php`

MODEL: `airuoft.php`

Model:

* retrieve all flights which are available for the given campus and date

Collect the following info:

* Departure time

Present user with list of available flights matching search parameters that have available space.

* This part is dynamically generated
* When user selects a flight, redirect to [Seat Selection](#seat-info)

##### Seat Info

URL: `seats.php`

MODEL: `airuoft.php`

Model:

* retrieve the occupied seats for the selected flight

Let user select seats by clicking on helicopter schema (see [CSC309 Website] [1])

* The available seats must be loaded from a database
* The diagram must be interactive
* Once the user selects an open seat, redirect to [Billing](#billing-info)

Color Scheme:

* Available seats are white
* Occupied seats are yellow
* Customer's current seat selection is green. 

Standard:

Let's standardize the seat indexing. Order is from left to right. There is a seat-sized space between `Seat 0` and `Seat 1`.

##### Billing Info

URL: `billing.php`

Collect the following:

* First and last name
* Credit card number
* Credit card expiration date

Enforce the following validation rules:

* No fields can be empty
* Credit card number must have 16 digits
* Expiration date should be a valid date (MM/YY)
* The card should not have expired

When a user confirms, redirect to [Summary](#summary-info)

##### Confirmation

URL: `confirmation.php` (using templating)

MODEL: `airuoft.php`

Model:
* submit all ticket info to it, and it writes the info into the Ticket table

Regurgitate all info, ask user to confirm

##### Summary Info

URL: `confirmation.php` (using templating)

Display customer's flight information:

* this means the page is auto-generated

Functionality:

* Provide a way to print the page

This is the final section of the customer chain. Allow redirect back to main page.

##### Simplifying Assumptions

* Tickets cannot be bought for the current date
* Tickets can be bough at most 2 weeks in advance
* Only 1 ticket can be bought at a time
* All tickets cost $20 

#### Administrating Flights (admin portal)

URL: `admin.php`

MODEL: `admin.php`

Model:

* Delete all flight and ticket data
* Populate the Flight for the next 14 days
* Return all tickets sold

Requires the following options:

* Delete all flight and ticket data
* Populate Flight table for next 14 days
* [List all sold tickets](#sold-ticket-info)

##### Sold Ticket Info

URL: `soldtickets.php`

MODEL: `admin.php`

This page is auto-generated from DB

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
* available INT = number of tickets left

About:
This table is used to keep track of *all* available flights. Initially, should be pre-filled with all flights for 2 days. I think.
**The only thing that should change in this table is the *available* field**

NOTE:

* update AVAILABLE on ticket purchase

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

## Third-Party Tools Used

### PHP

* CodeIgniter Framework
* FirePHP Console

NOTE about FirePHP:

1. FirePHP Console is only being used for development to better track errors

2. FirePHP is not included in this repo. You have to install it seperately. Instructions are [here](http://www.firephp.org/HQ/Learn.htm)


### Javascript

* JQuery

* JQuery UI (Redmond Theme)
