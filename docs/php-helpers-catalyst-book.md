```table with-header
Property	|	Value
Book Title	|	PHP Helpers Catalyst
Author		|	M
Year		|	2020
Language	|	English
Version		|	Working Draft 0.0.1
Format		|	Digital Text
Price		|	Free (CCPL License - Creative Commons Attribution)
Reading Age	|	21 years and up
```

#- PHP Helpers Catalyst
Book about programming computer applications with HTML, CSS and PHP.

* Everything about HTML, CSS and PHP.
* An introduction to Relational Database Management Systems (RDBMS).
* A short introduction to Dynamic HTML with Javascript (DHTML).

Dedicated to students looking for employment.

About the author:
20 years of experience working in a very large firm, programming applications with HTML, CSS, PHP and databases.

#- Index
[markdown-auto-index]
#+ 1. Introduction

```quote
Computer programming is about writing software applications for own use, for team use, or for use by everyone in your organization.
```

A computer has three parts -
1. A monitor for display
2. A processing unit to run programs/software applications
3. Input devices like a keyboard and a mouse

Software applications are written in a computer programming language. Examples are -
1. C
2. C++
3. Java
4. PHP

---

#+ 2. What is PHP
PHP is one of the many well known programming languages. It is designed specifically to be used with HTML, which is used for building `graphical user interface (GUI)` with XML (Extended Markup Language) like syntax.

There are three languages related to PHP - 

1. HTML - Hyper Text Markup Language
	* GUI part of an application
	* Viewed in a Web Browser like `Mozilla Firefox` or similar
2. CSS - Cascading Style Sheets
	* Colors, backgrounds and layouts part of an application
3. PHP itself - PHP: Hypertext Preprocessor
	* Interpreted programming language
	* Executable part of an application
	* Connects to a database for example

Before we study PHP, lets start with `HTML`, then `CSS` and finally `PHP`.

---

#+ 3. What is HTML
Hyper Text Markup Language is used to build graphical user interfaces for a web browser. 

A web browser is often associated with accessing the internet, websites and web pages. Looking closely, internet is not a requirement for building graphical user interfaces with HTML. An example of this is saving a webpage to your computer. It gets saved as file - `website.html`.

Required tools for writing GUI with HTML
1. Computer (without a network connection)
2. Text editor like `Gedit`
3. Web browser like `Firefox` to view the output

Here is a sample HTML code
```raw
<html>
	<head>
		<title>Sample HTML Page</title>
	</head>
	<body>
		<h1>This is a Heading</h1>
		<p>This is a paragraph</p>
		<a href="example.html">Link to Example page</a>
		<a href="example2.html">Link to Example 2 Page</a>
	</body>
</html>
```

---

##+ 3.1 What is a web browser

A web browser is an application that is used to view web pages, it usually comes pre-installed on a computer. Web pages are built using HTML and CSS.

```quote
A web browser is the display canvas that renders graphical user interfaces built using HTML and CSS.
```

Additionally a web browser connects to the internet to fetch web pages, which are rendered in the display canvas.

Steps a web browser follows
1. User enters a URL (Uniform Resoure Locator) into the web browser's address bar.
2. It connects to the internet and fetches the website/web page.
3. It displays/renders the fetched web page HTML and CSS in the display canvas.
	- Clicking a link in the display canvas, repeats the steps from 1, as if the user has entered the URL in the address bar.
	- Which creates a flow from one HTML GUI screen to another.

Internet is the networking component of a computer. Networking is of three kinds -
```table
**Internet** | WWW | World Wide Web | Examples are internet websites and internet email
**LAN** | Local area network | Network that is within the `organization`, and not connected to the internet. | Examples are organization websites and web applications, organization email.
**localhost** | Network that is internal to the computer, without needing a real network connection | Network that works only in the computer, not outside the computer. | Examples are localhost web applications
```

In this book we will focus on `localhost` PHP applications.

---
##+ 3.2 HTML in-depth

HTML is a markup language. It is designed to write formatted text, tables, graphics and layouts, by surrounding text with something called a `tag`.

Here is an example
```raw
<h1>This is a Heading</h1>
<p>This is a paragraph</p>
```
```table
<h1>	|	h1 tag represents heading 1
<p>	|	p tag represents paragraph
```


```quote
HTML is a markup language for writing formatted text, tables, graphics and layouts.
```


HTML mainly contains two keywords
1. Tag
2. Attribute

---

###+ 3.2.1 HTML Tag
A tag is one of the few pre-defined names specified by HTML specification, architectured by W3C - World Wide Web Consortium. It defines the formatting and specification of the enclosed text.

```table
Start tag	|	A tag starts with `less than (<)` symbol, `name` of the tag and `greater than (>)` symbol.
Text		|	Then the text, followed by tag close,
Close tag	|	which is `less than (<)` symbol, `forward slash ( / )`, `name` of the tag and `greater than (>)` symbol
```

Here is an example
```
<`html`>
	<`head`>
		<`title`>Sample HTML Page<`/title`>
	<`/head`>
	<`body`>
		<`h1`>This is a Heading<`/h1`>
		<`p`>This is a paragraph<`/p`>
		<`a` href="example.html">Link to Example page<`/a`>
		<`a` href="example2.html">Link to Example 2 Page<`/a`>
		<`img` src="graphic.jpg" />
	<`/body`>
<`/html`>
```

Here is the list of most used tags in HTML.

```table with-headers
Tag Name | Purpose
h1	|	Heading 1 text
h2	|	Heading 2 text
h3	|	Heading 3 text
h4	|	Heading 4 text
h5	|	Heading 5 text
p	|	Paragraph text
strong	| Bold text
em	| Italic/emphasized text
a	|	Anchor tag, for linking to another HTML page
img |	Image tag, for displaying images
div	|	Enclosure tag to group multiple HTML tags. It appears in a new line.
span	|	Enclosure tag to group multiple HTML tags. It does not create new line (inline).
```

Here is the list of additional tags used to define structure.

```table with-headers
Tag Name | Purpose
html	|	Root tag that contains all HTML
head	|	Head tag that contains information about the page, but not the content
title	|	Title of the page, placed within the head tag
body	|	Formatted text, tables, graphics must be placed within body tag
link	|	Link to a CSS stylesheet
style	|	CSS styles for the page
```

A few tags don't need a `close tag`, as in `<img>` tag in the example. These tags are called `self-closing` tags. They require a forward slash ( / ) at the end of the open tag.

---

###+ 3.2.2 HTML Attributes
An attribute is one of the few pre-defined names/properties applied to a HTML tag. It is encosled within quotes within the start tag.

Here is the list of most used tags in HTML. Not all attributes apply to all tags.

```table with-headers
Attribute Name | Purpose
href	|	Applied on anchor tag, that specifies a link to page
src		|	Source location of an image or graphic
class	|	List of CSS style class names
id		|	An identifier given to a tag
```

Here is a complete example
```
<html>
	<head>
		<title>Sample HTML Page</title>
	</head>
	<body>
		<h1 `id="main-heading"`>This is a Heading</h1>
		<p `class="color-green text-bold"`>This is a paragraph</p>
		<a `href="example.html"`>Link to Example page</a>
		<a `class="color-yellow"` `href="example2.html"`>Link to Example 2 Page</a>
		<img `src="graphic.jpg"` `width="100px"` `height="100px"` />
	</body>
</html>
```

###+ 3.2.3 HTML Practice

This section combines everything you learned about HTML and shows you how to write a HTML page on your computer.

Tools you need - 
1. Text editor like Gedit
2. Web browser like Firefox

Follow the below steps to create your first HTML page -
1. On your computer open the text editor and type the following HTML code.
	```raw
	<html>
	<head>
		<title>Example Page</title>
	</head>
	<body>
		<h1>Heading 1</h1>
		<p>This is a paragraph</p>
		<h2>Heading 2</h2>
		<p>Headings 1 to 6</p>
		<a href="example1.html">Link to Example 1 page</a>
		<a href="example2.html">Link to Example 2 Page</a>
		<div>
			<h3>More Paragraphs</h3>
			<p>One more paragraph</p>
		</div>
	</body>
	</html>
	```
2. Save the file as `example1.html`. Make sure the file extension is `.html`, not `.txt`.
3. Open `example1.html` in the web browser to view the output.

Similarly create `example2.html` for both the links to work correctly.

#+ 4. What is CSS

CSS (Cascading Style Sheets) is used to add colors, backgrounds, fonts and layouts to a HTML page.

```quote
CSS is a property/value list of definitions on how HTML must appear according to specified colors, backgrounds, fonts and layouts.
```

Here is an example -
```
h1{
	color: yellow;
	background: black;
}

p{
	color: white;
	font-family: "arial";
	font-size: 15px;
}

.special{
	color: #000000;
	background: #FFFFFF;
}

#main{
	color: black;
}
```

## CSS Selectors
CSS Selectors are used to select tags to apply styles.
```table
h1, p		|	Tag name selector		|	Apply specified styles to tag names
.special	|	Class name selector		|	Apply to tags with `class` attribute with value `special`
#main		|	Id/identifier selector	|	Apply to tag with `id` attribute with value `main`
```

##+ 4.1 CSS Colors

Colors are represented either by name of the color or by `hex color code`. Hex color codes are `RGB` values, representing `red`, `green`, `blue`.

R or G or B value in RGB starts from `00` to `99`, then `AA` to `FF`. This is called hexadecimal representation. A set of three hex codes between `00` and `FF` together become an RGB color. 

Here are a few examples

```table with-header
Name   |    #RGB ( #RRGGBB )
Black  |    `#` `00` `00` `00` = `#000000`
Silver |    `#` `C0` `C0` `C0` = `#C0C0C0`
Gray   |    `#` `CC` `CC` `CC` = `#CCCCCC`
White  |    `#` `FF` `FF` `FF` = `#FFFFFF`
```

Colors can be used either for text colors are text/box/layout backgrounds.

##+ 4.2 CSS Fonts

CSS font property can be used to define the font for a HTML text.

```table with-header
Type | font-family
Serif | """Times New Roman""", Times, serif
Serif | Georgia, serif
Serif | Garamond, serif
Sans-Serif | Arial, Helvetica, sans-serif
Sans-Serif | Tahoma, Verdana, sans-serif
Sans-Serif | """Trebuchet MS""", Helvetica, sans-serif
Sans-Serif | Geneva, Verdana, sans-serif
Monospace | """Courier New""", Courier, monospace
Cursive | """Brush Script MT""", cursive
```

##+ 4.3 CSS Practice

This section combines what you learned about CSS and shows you how to write CSS styles on your computer.

Tools you need - 
1. Text editor like Gedit
2. Web browser like Firefox

Follow the below steps to create your first CSS styles -
1. On your computer open the text editor and type the following CSS code.
	```raw
	h1{
		color: yellow;
		background: black;
	}

	p{
		color: white;
		font-family: "arial";
		font-size: 15px;
	}

	.special{
		color: #000000;
		background: #FFFFFF;
	}

	#main{
		color: black;
	}
	```
2. Save the file as `styles.css`. Make sure the file extension is `.css`, not `.txt`.
3. In `example1.html` file, within `<head>` tag
	- Type the line `<link rel="stylesheet" type="text/css" href="styles.css">`.
3. Open `example1.html` in the web browser to view the output.


##+ 4.4 CSS Box Model

In addition to colors and fonts, CSS supports margin, padding and border properties for every tag, on four sides. It is called the `CSS Box Model`.

```table
Margin  | Outside space around the text
Border  | Border line around the text
Padding | Inside space around the text
```

Here is an illustration -
```html
<div style="width:600px;">
	<div style="border:1px dashed #000; padding: 0 60px 40px 60px;">
		<p style="padding:0 0 10px 0;">Margin</p>
		<div style="border:1px solid #000; padding: 0 60px 40px 60px;">
			<p style="padding:0 0 10px 0;">Border</p>
			<div style="border:1px dashed #000; padding: 0 60px 40px 60px;">
				<p style="padding:0 0 10px 0;">Padding</p>
				<div style="border:1px dotted #000; padding: 20px 0px; text-align:center; font-weight:bold;">
					<p style="margin:0;">Text/image/content</p>
				</div>
			</div>
		</div>
	</div>
</div>
```

### CSS property names
```table
margin-left     | Margin to the left
margin-right    | Margin to the right
margin-top      | Margin on the top
margin-bottom   | Margin at the bottom
padding-left    | Padding to the left
padding-right   | Padding to the right
padding-top     | Padding on the top
padding-bottom  | Padding at the bottom
border-left     | Border to the left
border-right    | Border to the right
border-top      | Border on the top
border-bottom   | Border at the bottom
```

Example
```
p{
	margin-left: 10px;
	padding-top: 20px;
	border-right: 1px solid #000000;
}
```

There is a `shorthand`(shortcut) for writing values for all four sides at once.
```table
margin  | TOPpx  RIGHTpx BOTTOMpx LEFTpx
padding | TOPpx  RIGHTpx BOTTOMpx LEFTpx
border  | BORDERpx BORDER-STYLE BORDER-COLOR
```

Example
```
p{
	margin: 10px 10px 10px 10px;
	padding: 10px 10px 10px 10px;
	border: 1px solid #000000;
}
```

Additionally, border property supports the below property names.
```table
border-width  |  WIDTHpx
border-color  |  #RGB
border-style  |  solid or dotted or dashed or ridged
```

##+ 4.5 CSS Layouts

##+ 4.6 CSS Concepts

#+ 5. PHP In-depth

##+ 5.1 What is http

###+ 5.1.1 Apache Web Server

###+ 5.1.2 PHP Built-in Web Server

###+ 5.1.3 PHP CLI

##+ 5.2 PHP Key Concepts

###+ 5.2.1 PHP Constants

###+ 5.2.2 PHP Variables

###+ 5.2.3 PHP Arrays

###+ 5.2.4 PHP Associative Arrays

###+ 5.2.5 PHP User-defined Functions

###+ 5.2.6 PHP Object Oriented Programming

###+ 5.2.7 PHP Templates

##+ 5.3 PHP Built-in functions

#+ 6. PHP Helpers Library

#+ 7. What is RDBMS - Relational Database Management Systems

##+ 7.1 SQL and MySQL

##+ 7.2 Database Tables

##+ 7.3 Database Normalization

##+ 7.4 MySQL and PHP

#+ 8. What is Javascript

---

Work in progress...

---


#+ 9. Exercises

Here are a few exercise projects for practice. Choose three or more to gain more skill on HTML, CSS and PHP programming.

1. Your personal website
	* Create your own personal website on your computer with five or more HTML pages.
2. Contact form
	* Create a PHP contact form with `Name`, `Email`, and `Question` fields. Save the responses to a file or a database table.
3. Notes application
	* Create a PHP application with a text form field, and save the results to a text file.
4. Calculator application
	* Create a PHP application to add, substract, multiply or divide two numbers, with two text inputs and a submit button.
5. Calendar application
	* Create a HTML or PHP application with calendar for current year, using HTML table tag.
6. Comments application
	* Create a PHP application for users to read and write comments. Save the results to a text file or a database table.
7. Photos application
	* Create a HTML or PHP application to display photos in a folder in gallery format.
8. Music/Songs listing
	* Create a HTML page with your favorite music or songs list.
9. Dictionary listing
	* Create a HTML page with a few words and their meanings from the dictionary.
10. Todo List
	* Create a HTML page with a list of todo items.
11. Font listing
	* Create a HTML page with a list of all fonts along with sample text.
12. PHP Info
	* Create a PHP page with `php_info()` function and review the output.
13. Color palettes
	* Create a HTML or PHP page with a list of color names, hex color codes and `background-color` blocks.

---

#+ 10. References
1. World Wide Web Consortium (W3C)
	- http://www.w3.org/html
	- http://www.w3.org/css
	- http://www.w3.org/wiki/Common_HTML_entities_used_for_typography
2. Official Apache Web Server Documentation
	- http://httpd.apache.org
3. Official MySQL Documentation
	- http://dev.mysql.com/doc/
4. Official PHP Documentation
	- http://www.php.net/docs.php
5. Mozilla Developer Network (MDN)
	- http://developer.mozilla.org
	- http://firefox.com
6. Gedit
	- http://wiki.gnome.org/Apps/Gedit
