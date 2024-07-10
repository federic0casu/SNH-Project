# importing modules 
import mysql.connector

from reportlab.pdfgen import canvas 
from reportlab.lib import colors 


def generate_pdf_for_book(title, isbn): 
    file_name = f'{isbn}.pdf'
    document_title = title
    text_lines = [ 
        "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod"    ,
        "tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim "      ,
        "veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea "   ,    
        "commodo consequat. Duis aute irure dolor in reprehenderit in voluptate "    ,
        "velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat ", 
        "cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id " ,
        "est laborum."
    ] 
    image = 'image.png'

    # creating a pdf object 
    pdf = canvas.Canvas(file_name) 

    # setting the title of the document 
    pdf.setTitle(document_title) 

    # creating the title by setting its font 
    # and putting it on the canvas 
    pdf.setFont('Helvetica', 24) 
    pdf.drawCentredString(300, 770, title) 

    # drawing a line 
    pdf.line(30, 710, 550, 710) 

    # creating a multiline text using 
    # textLines and a for loop 
    text = pdf.beginText(40, 680) 
    text.setFont('Helvetica', 12) 
    text.setFillColor(colors.black) 
    for line in text_lines: 
        text.textLine(line) 
    pdf.drawText(text) 

    # drawing an image at the 
    # specified (x, y) position 
    pdf.drawInlineImage(image, x=150, y=25, width=300, height=75)  

    # saving the pdf 
    pdf.save() 

# MySQL database connection configuration
db_config = {
    'host': 'localhost',
    'port': 3306,
    'user': 'book_shop',
    'password': 'book_shop_password',
    'database': 'book_shop_db'
}

# Connect to MySQL database
try:
    conn = mysql.connector.connect(**db_config)
    cursor = conn.cursor()

    # Query books table for book title, author, and large image URL
    query = "SELECT book_title, isbn FROM books"
    cursor.execute(query)

    # Fetch all books
    books = cursor.fetchall()

    # Generate PDF for each book
    for book in books:
        title = book[0]
        isbn = book[1]
        
        # Generate PDF for the current book
        generate_pdf_for_book(title, isbn)
        
        print(f"Generated PDF for {title} (isbn: {isbn})")

except mysql.connector.Error as err:
    print(f"Error: {err}")

finally:
    if 'conn' in locals() and conn.is_connected():
        cursor.close()
        conn.close()