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

function updateResults(response) {
    $("#result-section").empty();

    if (response.length > 0) {
        var h2 = $(`<h2>Results<h2>`);
        $("#result-section").append(h2);
        
        var row = $("<div class='row'></div>");

        $.each(response, function (i, book) {
            var bookDiv = $("<div class='book'></div>");

            bookDiv.html(`
                <img src="${book.image}" alt="${book.title}">
                <h3>${book.title}</h3>
                <p>Author: ${book.author}</p>
                <button onclick="location.href='../php/update_cart.php?isbn=${book.isbn}&action=1'">Add to Cart</button>
            `);

            row.append(bookDiv);

            // Create a new row after every three books
            if ((i + 1) % 3 === 0 || i === response.length - 1) {
                $("#result-section").append(row);
                row = $("<div class='row'></div>");
            }
        });
    } else {
        var message = $("<p>No books found.</p>");
        $("#result-section").append(message);
    }
}