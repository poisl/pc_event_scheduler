EXT:pc\_event\_scheduler
========================

|Code Climate|

Description
-----------

The event scheduler can be used for planning of recurring events.
Example: If you are in a small sports club, you could plan weekly games.
Members will be able to join or cancel, so other members can see who participates in the game.
You can plan vacation schedules, where no events will be scheduled.
For past events you can provide event statistics to the participants.
Last but not least you can notify users that have not accepted or canceled the event yet by email.

Installation
------------

-  Include TypoScript to root page.
-  Create a folder to store the events, vacations and participants.
-  Insert the plugin for the event planner on your desired page.
-  Insert the plugin for holiday management on the desired page.
-  Insert the plugin for event statistics on the desired page.
-  Edit setup of the root template and change settings to your needs.

Plugin TS setup in root template
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

::

    plugin.tx_pceventscheduler {
            # Id of the folder where all events, vacations and participants are stored
            persistence.storagePid = 123
            settings {
              # Event start time (H:M); Default value, if not set is "20:00"
              eventStartTime = 20:00
              # Event end time (H:M); Default value, if not set is "22:00"
              eventEndTime = 22:00
              # Name of the event default location; Default value, if not set is "Default location"
              defaultLocation = Default event location
              # Name of the alternate event default location, that can be used by eventAdmins to set the event location
              alternateLocation = Alternate event location
              # Weekday of the event is (Monday to Sunday); Default value, if not set is "Friday"
              eventWeekday = Friday
              # Event is repeated after amount of weeks specified here; Default value, if not set is "1"
              repeatEventInterval = 1
              # Id of the frontend user group that contains all frontend users that should be able to participate
              participantGroupId = 123              
              # Id of the frontend user group that will be able to manage holidays, activate/deactivate events and set event location
              eventAdminGroupId = 123
              # Id of the page where the event planner is included
              eventPageUid = 123
              # Email subject for the email notification; Default value, if not set "Event notification"
              notifyMailSubject = Event notification
              # Email body for the email notification in html style; Default value is set
              notifyMailBody = Hello ###name###,<br><br>on ###eventdate### at ###eventtime### o'clock there is an event in ###eventlocation###. You have neither accepted or canceled this event yet, please do so.<br><br><a href='###acceptlink###'>ACCEPT</a><br><br>or<br><br><a href='###cancellink###'>CANCEL</a><br><br>Best regards<br><br>This is an automated message, please don't reply to it.
    }
    # DO NOT REMOVE: This line is required, that the email notify task has access to the plugin settings
    module.tx_pceventscheduler < plugin.tx_pceventscheduler
    
Notify Participants by mail
---------------------------

-  If you'd like to notify the participants that did not accept or cancel the next event by email, you can setup a scheduler task.
-  Make sure you have setup the scheduler extension properly.
-  Add a new "Extbase-CommandController-Task" and select CommandController Command "PcEventScheduler Notify: notify".
-  Setup the start and repeat time as you wish, everytime this task runs it will notify all participants about the next event, that did not yet accept or cancel it.
-  The email will not only contain details about the next event but also links to accept or cancel the participation.

.. |Code Climate| image:: https://codeclimate.com/github/poisl/pc_event_scheduler/badges/gpa.svg
:target: https://codeclimate.com/github/poisl/pc_event_scheduler
