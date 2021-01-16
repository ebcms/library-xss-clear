# xss

xss clear

## Installation

``` 
composer require ebcms/xss-clear
```

## Usage

``` php

$xss = new \Ebcms\Xss();

$html = <<<'str'
<script>alert('xss');</script>
<a href="&#x2000;javascript:alert('xss');" class="dudu">dud>u</a>
<a href="\u0001java\u0003script:alert(1)" class="dudu">dud>u</a>
<a href="&#x6A&#x61&#x76&#x61&#x73&#x63&#x72&#x69&#x70&#x74&#x3A&#x61&#x6C&#x65&#x72&#x74&#x28&#x27&#x58&#x53&#x53&#x0027&#x29">dd</a>
<p test>hello</p>
<p href="http://www.foo.com">world!</p>
<p>bad tag.<foo>bar</foo>
<span style="color:red;">spancontent.
str;

echo $xss->clear($html);

# <a>dud&gt;u</a>
# <a>dd</a>
# <p>hello</p>
# <p>world!</p>
# <p>bad tag.
# <span style="color:red;">spancontent.</span></p>

$white_list=[
	'a'=>['href'],
	'p'=>[],
	'span'=>[]
];
echo $xss->clear($html, $white_list);

# <a>dud&gt;u</a>
# <a>dd</a>
# <p>hello</p>
# <p>world!</p>
# <p>bad tag.
# <span>spancontent.</span></p>
```

## HTML Character

``` php
# Hello, i try to <script>alert('Hack');</script> your site
# <p>Hello, i try to  your site</p>
```

## Hexadecimal HTML Character

``` php
# <IMG SRC=&#x6A&#x61&#x76&#x61&#x73&#x63&#x72&#x69&#x70&#x74&#x3A&#x61&#x6C&#x65&#x72&#x74&#x28&#x27&#x58&#x53&#x53&#x27&#x29>

# <img>
```

## Unicode Hex Character

``` php
# <a href='&#x2000;javascript:alert(1)'>CLICK</a>

# <a>CLICK</a>
```

## Unicode Character

``` php
# <a href="\u0001java\u0003script:alert(1)">CLICK</a>

# <a href="%5Cu0001java%5Cu0003script:alert(1)">CLICK</a>
```

## non Inline CSS

``` php
# <li style="list-style-image: url(javascript:alert(0))">11</li>

# <li>11</li>
```

## check if an string contains a XSS attack

``` php
# \x3cscript src=http://www.example.com/malicious-code.js\x3e\x3c/script\x3e

# <p>\x3cscript src=http://www.example.com/malicious-code.js\x3e\x3c/script\x3e</p>
```

## allow e.g.iframes

``` php
# <iframe width="560" onclick="alert('xss')" height="315" src="https://www.youtube.com/embed/foobar?rel=0&controls=0&showinfo=0" frameborder="0" allowfullscreen></iframe>

# <iframe width="560" height="315" src="https://www.youtube.com/embed/foobar?rel=0&controls=0&showinfo=0" frameborder="0" allowfullscreen></iframe>
```

