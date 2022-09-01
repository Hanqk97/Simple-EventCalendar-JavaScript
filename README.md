# CSE330
## Group Member

- Name: Hanqi Chen
- Student ID: 505891
- Github: Hanqk97: https://github.com/Hanqk97

## Register and Log in

- New Users can click on the area where "Click here to register" is located and use the pop-up form to register and log in.
- Users can browse the calendar without logging in, but cannot view any time

## Functions

- Add New Event: Users can use the pop-up form to add a new event, the maximum of title is 10 spaces, the date needs to be between 1 and 31, in some months such as February fill in the non-existent date such as 30 will lead to the event can not be displayed.

- Edit Event: Users need to enter the event id (the number displayed after the event in the calendar) and the new information to edit the event.

- Delete Event: Users need to enter the event id to delete event.

- View Event: Users need to enter the event id to view event's details(title, datetime, tag).

## Database

- Following is the structure of database and initial data of events table.
  
![strucuture](https://github.com/cse330-summer-2022/module5-group-505891/blob/master/table%20structure.png)
![table](https://github.com/cse330-summer-2022/module5-group-505891/blob/master/events%20table.png)   
  
## Web Security

- All AJAX requests that either contain sensitive information or modify something on the server are performed via POST.

- All input content to php file are transfered by htmlentities functions before output, which is safe from XSS attacks and SQL Injection.

- CSRF tokens are passed when adding/editing/deleting/viewing/sharing/searching events.

- Only stored hash password in user table.

- The "ini_set("session.cookie_httponly", 1);" was added before all the "session_start();" to make Session cookie be HTTP-Only .
  

## Creative Portion

- Each event can be shared to other user through clicking share button and filling form.

- Add tag for each event, user can free add tag for every event according their need.

- User can search their events base on tags.(tags must be identical).

- Use the toggle function instead of the normal buttons, such as register and logout, just click in the text area, no need to click the buttons.

- All forms appear as pop-ups, and they can be dragged and dropped at will.


