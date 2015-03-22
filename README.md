wpboilerplate
=============

wpboilerplate is a powerful quickstart [WordPress](https://wordpress.org) theme made for developers. It's based on [Roots](https://roots.io), [HTML5 Boilerplate](https://html5boilerplate.com), [960 Grid System](http://960.gs), [LessCSS](http://lesscss.org) and [Starkers](http://viewportindustries.com/products/starkers). It will help you rapidly create sites!

Source: https://github.com/optimumweb/wpboilerplate

## Installation

Install wpboilerplate under the *wp-content/themes* directory like you would with any theme.

    git clone git://github.com/optimumweb/wpboilerplate.git

Then, install the [wpboilerplate-child](https://github.com/optimumweb/wpboilerplate-child) child-theme under the same *wp-content/themes* directory. Make sure to name this child-theme according to your theme name.

    git clone git://github.com/optimumweb/wpboilerplate-child.git my-child-theme

You should never have to modify the wpboilerplate theme. You should be working from the wpboilerplate-child theme to customize it to your needs.

To make sure you won't be modifying the original wpboilerplate-child theme, you should remove the origin and add yours.

    cd my-child-theme
    git remote rm origin
    git remote add origin <my_child_theme_url>

## Author

[Jonathan Roy](https://twitter.com/jonathanroy), [OptimumWeb](http://optimumweb.ca)

## License

Licensed under the MIT license.

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.