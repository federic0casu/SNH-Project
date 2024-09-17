$(document).ready(function () {
    $('#search_books_form').submit(function (e) {
        // Prevent the form from submitting in the traditional way
        e.preventDefault();

        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: $(this).serialize(),
            dataType: 'json',
            success: function (response) {
                updateResults(response);
            },
            error: function (xhr, status, error) {
                console.error('AJAX request failed with status', status, 'and error', error);
            }
        });
    });
});

function sanitize(input) {
    // Create a map of characters to their HTML entity equivalents
    const entities = {
        '"': '&quot;',
        "'": '&apos;',
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;'
    };

    return input.replace(/[\"'&<>]/g, char => entities[char]);
}

function updateResults(response) {
    // Clear the result section
    const result = document.getElementById("result-section");
    result.innerHTML = "";

    console.log("Got books: ")
    if (response.length > 0) {
        console.log(response)
        const h2 = document.createElement("h2");
        h2.textContent = "Results";
        result.appendChild(h2);

        let row = document.createElement("div");
        row.className = "row";

        $.each(response, function (i, book) {

             // Create the book div
             const bookDiv = document.createElement("div");
             bookDiv.className = "book";
 
             // Create and append the image element
             const img = document.createElement("img");
             img.src = sanitize(book.image);
             img.alt = sanitize(book.title);
             bookDiv.appendChild(img);
 
             // Create and append the title element
             const h3 = document.createElement("h3");
             h3.append(document.createTextNode(`${book.title}`));               // << Notice document.createTextNode('...') >>
             bookDiv.appendChild(h3);
 
             // Create and append the author
             const author = document.createElement("p");
             author.append(document.createTextNode(`Author: ${book.author}`));  // << Notice document.createTextNode('...') >>
             bookDiv.appendChild(author);
 
             // Create the form element
             const form = document.createElement("form");
             form.action = "../php/update_cart.php";
             form.method = "post";
 
             // Create and append the hidden input for ISBN
             const isbnInput = document.createElement("input");
             isbnInput.type = "hidden";
             isbnInput.name = "isbn";
             isbnInput.value = sanitize(book.isbn);
             form.appendChild(isbnInput);
 
             // Create and append the hidden input for CSRF token
             const csrf = document.createElement("input");
             csrf.type = "hidden";
             csrf.name = "csrf_token";
             csrf.value = sanitize(book.csrf_token);
             form.appendChild(csrf);
 
             // Create and append the hidden input for action
             const action = document.createElement("input");
             action.type = "hidden";
             action.name = "action";
             action.value = "1";
             form.appendChild(action);
 
             // Create and append the submit button
             const submit = document.createElement("input");
             submit.type = "submit";
             submit.value = "Add to Cart";
             form.appendChild(submit);
 
             // Append the form to the book div
             bookDiv.appendChild(form);
 
             // Append the book div to the row
             row.appendChild(bookDiv);

            // After every three books, append the row to the result section and start a new row
            if ((i + 1) % 3 === 0) {
                result.appendChild(row);
                row = document.createElement("div");
                row.className = "row";
            }
        });
        result.appendChild(row);
    } else {
        // If no books were found, display a message
        const message = document.createElement("p");
        message.textContent = "No books found.";
        result.appendChild(message);
    }
}