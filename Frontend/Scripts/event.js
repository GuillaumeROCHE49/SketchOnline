function OnGetEventsSuccess(data) {
    // data = {
    //     events: [
    //         {
    //             id: 'xxxx',
    //             title: 'xxxx',
    //             theme: 'xxxx',
    //             image: 'xxxx',
    //             status: 'xxxx'
    //         },
    //         ...
    //     ]
    // };

    // Loop through the events
    for (var i = 0; i < data.events.length; i++) {
        // Add the event to the event list
        AddEvent(data.events[i]);
    }
}

function OnGetEventsError(jqXHR, textStatus, errorThrown) {
    var errorMessage = "Erreur AJAX lors de la connexion : " + textStatus;
    if (errorThrown) {
        errorMessage += "\n" + errorThrown;
    }
    alert(errorMessage);
}

function GetEvents() {
    var url = "https://localhost:5001/Backend/event.php";
    var data = {
        events: [],
        infos: ["id", "title", "theme", "image", "status"]
    }

    $.ajax({
        url: url,
        type: "GET",
        data: data,
        contentType: "application/json",
        success: function(data) {
            OnEventsSuccess(data);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            OnEventsError(jqXHR, textStatus, errorThrown);
        }
    });
}

function AddEvent(event) {
    /*
    event = {
        id: 'xxxx',
        title: 'xxxx',
        theme: 'xxxx',
        image: 'xxxx',
        status: 'xxxx'
    };
    */
    
    // The goal is to add an event (exemple above) to the event list (div with id="events-container")

    // Create the post
    var eventPost = document.createElement('div');
    eventPost.classList.add('event-post');

    // Create the id text
    var eventText16 = document.createElement('span');
    eventText16.classList.add('event-text16');

    var idSpan = document.createElement('span');
    idSpan.innerHTML = event.id;
    eventText16.appendChild(idSpan);

    var br = document.createElement('br');
    eventText16.appendChild(br);

    eventPost.appendChild(eventText16);

    // Create the image
    var image = document.createElement('img');
    image.id = 'image';
    image.alt = 'image';
    image.src = event.image;
    image.classList.add('event-image1');
    eventPost.appendChild(image);

    // Create the title
    var title = document.createElement('h1');
    var titleSpan = document.createElement('span');
    titleSpan.innerHTML = event.title;
    title.appendChild(titleSpan);
    var br = document.createElement('br');
    title.appendChild(br);
    eventPost.appendChild(title);

    // Create the theme
    var themeSpan = document.createElement('span');
    themeSpan.innerHTML = event.theme;
    eventPost.appendChild(themeSpan);

    // Add the post to the event list
    var eventsContainer = document.getElementById('events-container');
    eventsContainer.appendChild(eventPost);
}


/*
<div class="event-post">
    <span class="event-text16">
        <span>Id unique</span>
        <br />
    </span>
    <img
        id="image"
        alt="image"
        src="https://play.teleporthq.io/static/svg/default-img.svg"
        class="event-image1"
    />
    <h1>
        <span>Title</span>
        <br />
    </h1>
    <span>Theme du concours</span>
</div>
*/