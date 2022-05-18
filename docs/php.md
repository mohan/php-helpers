[markdown-auto-index heading="Index"]

---

# HTML

HTML (Hyper Text Markup Language) is a computer language for writing formatted text on a computer.

## Uses
Websites are built using HTML.

Example:
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
	<a href="example.html">Link to Example page</a>
	<a href="example2.html">Link to Example 2 Page</a>
	<div>
		<h3>More Paragraphs</h3>
		<p>One more paragraph</p>
		<p class='special'>One more paragraph</p>
		<p id='poem'>This a Haiku.</p>
	</div>
</body>
</html>
```

1. Type the above code in a text editor like `notepad`.
2. Save it as `example.html`.
	- Make sure the file extension is `.html`, not `.txt`.
3. Open `example.html` in a web browser to view the output.
4. Similarly, create `example2.html` for the second link to work correctly.

## Keywords
* Tags
	- `<h1>` to `<h6>` heading tags
	- Link (Anchor) - `<a>` tag
	- Paragraph - `<p>` tag
	- Image tag - `<img src="graphic.jpg" />`
		- `graphic.jpg` must be present in the same location as the `html` file.
* Attributes
	- Properties assigned to a tag are called attributes.
	- Example: `href`, `src`, `class`, `id`.


---


# CSS

CSS (Cascading Style Sheets) are used to add colors and layouts to a HTML page.

Example:
```
h1{
	color: yellow;
	background: darkgray;
}

h2{
	color: violet;
}

p{
	color: darkgray;
	font-family: 'arial';
	font-size: 15px;
}

.special{
	color: black;
}

#peom{
	color: aliceblue;
}
```

1. Type the above code in a text editor like `notepad`.
2. Save it as `styles.css`.
	- Make sure the file extension is `.css`, not `.txt`.
3. In `example.html` file, within `<head>` tag
	- Type the line `<link rel="stylesheet" type="text/css" href="styles.css">`.
3. Open `example.html` in a web browser to view the output with colors.

## CSS Properties

```table
Name		|	Description
color		|	Text color (Color name or Hex color - #RGB - Red Green Blue)
background	|	background color
font-family	|	Text font
font-size	|	Text size
font-weight	|	font-weight: bold; for bold text
```

## Keywords

* Property name - `color`
* Property value - `green`
* CSS Class selector - `.special`
	- One class can be applied to multiple HTML tags.
	- Multiple class names can be applied to a HTML tag - `<p class="special quote large-paragraph">Example paragraph</p>`
* CSS ID selector -	`#poem`
	- An identifier can be applied to only one HTML tag.

### Color Names

```table
Name   |    #RGB
black  |    #000000 
silver |    #c0c0c0 
gray   |    #808080 
white  |    #ffffff 
maroon |    #800000 
red|    #ff0000 
purple |    #800080 
fuchsia|    #ff00ff 
green  |    #008000 
lime   |    #00ff00 
olive  |    #808000 
yellow |    #ffff00 
navy   |    #000080 
blue   |    #0000ff 
teal   |    #008080 
aqua   |    #00ffff 
orange |    #ffa500 
aliceblue  |    #f0f8ff 
antiquewhite   |    #faebd7 
aquamarine |    #7fffd4 
azure  |    #f0ffff 
beige  |    #f5f5dc 
bisque |    #ffe4c4 
blanchedalmond |    #ffebcd 
blueviolet |    #8a2be2 
brown  |    #a52a2a 
burlywood  |    #deb887 
cadetblue  |    #5f9ea0 
chartreuse |    #7fff00 
chocolate  |    #d2691e 
coral  |    #ff7f50 
cornflowerblue |    #6495ed 
cornsilk   |    #fff8dc 
crimson|    #dc143c 
cyan|   #00ffff 
darkblue   |    #00008b 
darkcyan   |    #008b8b 
darkgoldenrod  |    #b8860b 
darkgray   |    #a9a9a9 
darkgreen  |    #006400 
darkgrey   |    #a9a9a9 
darkkhaki  |    #bdb76b 
darkmagenta|    #8b008b 
darkolivegreen |    #556b2f 
darkorange |    #ff8c00 
darkorchid |    #9932cc 
darkred|    #8b0000 
darksalmon |    #e9967a 
darkseagreen   |    #8fbc8f 
darkslateblue  |    #483d8b 
darkslategray  |    #2f4f4f 
darkslategrey  |    #2f4f4f 
darkturquoise  |    #00ced1 
darkviolet |    #9400d3 
deeppink   |    #ff1493 
deepskyblue|    #00bfff 
dimgray|    #696969 
dimgrey|    #696969 
dodgerblue |    #1e90ff 
firebrick  |    #b22222 
floralwhite|    #fffaf0 
forestgreen|    #228b22 
gainsboro  |    #dcdcdc 
ghostwhite |    #f8f8ff 
gold   |    #ffd700 
goldenrod  |    #daa520 
greenyellow|    #adff2f 
grey   |    #808080 
honeydew   |    #f0fff0 
hotpink|    #ff69b4 
indianred  |    #cd5c5c 
indigo |    #4b0082 
ivory  |    #fffff0 
khaki  |    #f0e68c 
lavender   |    #e6e6fa 
lavenderblush  |    #fff0f5 
lawngreen  |    #7cfc00 
lemonchiffon   |    #fffacd 
lightblue  |    #add8e6 
lightcoral |    #f08080 
lightcyan  |    #e0ffff 
lightgoldenrodyellow   |    #fafad2 
lightgray  |    #d3d3d3 
lightgreen |    #90ee90 
lightgrey  |    #d3d3d3 
lightpink  |    #ffb6c1 
lightsalmon|    #ffa07a 
lightseagreen  |    #20b2aa 
lightskyblue   |    #87cefa 
lightslategray |    #778899 
lightslategrey |    #778899 
lightsteelblue |    #b0c4de 
lightyellow|    #ffffe0 
limegreen  |    #32cd32 
linen  |    #faf0e6 
magenta|    #ff00ff 
mediumaquamarine   |    #66cdaa 
mediumblue |    #0000cd 
mediumorchid   |    #ba55d3 
mediumpurple   |    #9370db 
mediumseagreen |    #3cb371 
mediumslateblue|    #7b68ee 
mediumspringgreen  |    #00fa9a 
mediumturquoise|    #48d1cc 
mediumvioletred|    #c71585 
midnightblue   |    #191970 
mintcream  |    #f5fffa 
mistyrose  |    #ffe4e1 
moccasin   |    #ffe4b5 
navajowhite|    #ffdead 
oldlace|    #fdf5e6 
olivedrab  |    #6b8e23 
orangered  |    #ff4500 
orchid |    #da70d6 
palegoldenrod  |    #eee8aa 
palegreen  |    #98fb98 
paleturquoise  |    #afeeee 
palevioletred  |    #db7093 
papayawhip |    #ffefd5 
peachpuff  |    #ffdab9 
peru   |    #cd853f 
pink   |    #ffc0cb 
plum   |    #dda0dd 
powderblue |    #b0e0e6 
rosybrown  |    #bc8f8f 
royalblue  |    #4169e1 
saddlebrown|    #8b4513 
salmon |    #fa8072 
sandybrown |    #f4a460 
seagreen   |    #2e8b57 
seashell   |    #fff5ee 
sienna |    #a0522d 
skyblue|    #87ceeb 
slateblue  |    #6a5acd 
slategray  |    #708090 
slategrey  |    #708090 
snow   |    #fffafa 
springgreen|    #00ff7f 
steelblue  |    #4682b4 
tan|    #d2b48c 
thistle|    #d8bfd8 
tomato |    #ff6347 
turquoise  |    #40e0d0 
violet |    #ee82ee 
wheat  |    #f5deb3 
whitesmoke |    #f5f5f5 
yellowgreen|    #9acd32 
```

---

# PHP

PHP is a scripting/templating computer programming language.

Example:
```
<html>
<head>
	<title>Example Page</title>
</head>
<body>
	<?php

	echo "<h1>Hello from PHP.</h1>";
	echo "<p>";
	echo 1+2+3;
	echo "</p>";

	?>

	<p>This is a simple calculation. 1+2+3 = 6.</p>

	<?php include "footer.php"; ?>
</body>
</html>
```
footer.php
```
<p>This is an example PHP page.</p>
<p>Use php include to include repeating HTML from a file.</p>
```

Output in Web Browser:
```
Hello from PHP.
6
This is a simple calculation. 1+2+3 = 6.

This is an example PHP page.
Use php include to include repeating HTML from a file.
```

1. Type the above code in a text editor like `notepad`.
2. Save it as `example.php`.
	- Make sure the file extension is `.php`, not `.txt`.
3. Setup `PHP` program on your computer.
4. Run `php -S localhost:8000` in `command line`.
5. Open `http://localhost:8000/example.php` in a web browser to view the output.

## Uses

* Websites (HTML/CSS - Information Technology)
* Dynamic HTML Web Applications
* Contact forms
* Data Entry forms
* Payment forms
* Database applications

## Keywords
```table
Name	|	Description
Constants	|	Property values that don't change. - define('RADIUS', 100);
Variables	|	Property values that may be changed as needed. - $example = 6;
Arrays		|	List of values - $examples = [1, 2, 3];
Associative arrays |	List of property value pairs - $examples = ["a"=>1, "b"=>2, "c"=>3];
Functions	|	PHP Code that can be used to process variables. - array_sum($examples) == 6;
```






---

# Handy Code Snippets

## HTML Layout: Left sidebar
```php raw
<html>
<head>
	<title>Left sidebar</title>
	<style type="text/css">
		#sidebar{
			float: left;
			width: 20%;
			background: yellow;
			color: white;
		}

		#main{
			float: left;
			width: 80%;
			background: lightgray;
			color: black;
		}

		/* Note: Invisible tag to clear float left */
		.clear:after{
			content: " ";
			clear: both;
			display: block;
		}
	</style>
</head>
<body>

	<div class='clear'>
		
		<div id='sidebar'>
			<!-- ul - unordered list, ol - ordered number list -->
			<ul>
				<!-- li - List item -->
				<li>
					<a href='page1.html'>Page 1</a>
				</li>
				<li>
					<a href='page2.html'>Page 2</a>
				</li>
			</ul>
		</div>

		<div id='main'>
			<h1>This is page 1.</h1>
		</div>

	</div>

</body>
</html>
```

## HTML Layout: Navigation Bar
```php raw
<html>
<head>
	<title>Navigation bar</title>
	<style type="text/css">
		#navbar{
			background: yellow;
			color: white;
			/* padding: top-pixels right-pixels bottom-pixels left-pixels ; */
			padding: 20px 20px 20px 20px;
		}

		#navbar ul li{
			float: left;
			padding: 20px 20px 20px 20px;
		}

		#main{
			background: lightgray;
			color: black;
			margin: 20px 20px 20px 20px;
		}

		/* Note: Invisible tag to clear float left */
		.clear:after{
			content: " ";
			clear: both;
			display: block;
		}
	</style>
</head>
<body>
		
	<div id='navbar'>
		<ul class='clear'>
			<li>
				<a href='page1.html'>Page 1</a>
			</li>
			<li>
				<a href='page2.html'>Page 2</a>
			</li>
		</ul>
	</div>

	<div id='main'>
		<h1>This is page 1.</h1>
	</div>

</body>
</html>
```

