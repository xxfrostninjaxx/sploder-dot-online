
HTML Treemap Implementation
by Geoff Gaudreault
http://www.neurofuzzy.net

This is provided AS-IS with no warranty express or implied.
Use this code, or any code that I write, at your own risk.

Good luck, starfighter!

Function: render_treemap
Recursive function that returns an HTML treemap based on an list of items

Parameters:
$theArray - treemapped items; associative array where key is the item name and value is the quantity

$width - the width of the treemap
$height - the height of the treemap
$depth - the current recursion depth, starts at 0
$orientation - 0 starts dividing vertically, 1 starts dividing horizontally.  This basically swaps the aspect ratio of the cells

This shizzle is nizzled in PHP, so you'll need that.