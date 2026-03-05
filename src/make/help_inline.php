<?php
require_once '../content/initialize.php';
require(__DIR__.'/../content/disablemobile.php'); ?>
<?php include_once('../config/env.php'); ?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <title>Sploder</title>
    <style type="text/css" media="print">
    body {
        font-family: Verdana, Arial, Helvetica, sans-serif;
    }

    a {
        color: #000;
        font-weight: bold;
        text-decoration: none;
    }

    p {
        line-height: 20pt;
        margin-bottom: 20pt;
    }

    ul {
        margin-bottom: 10pt;
    }

    ul ul {
        margin-top: 6pt;
    }

    ul li {
        margin-bottom: 10pt;
        list-style-type: none;
    }

    ul li li {
        margin-bottom: 0;
    }

    ul li a {
        font-weight: bold;
    }

    ul li ul li a {
        font-weight: normal;
    }

    img {
        padding: 8pt;
        margin: 8pt;
        background: #000000;
        vertical-align: middle;
    }

    li img,
    p img {
        margin-left: 0;
    }
    </style>

    <style type="text/css" media="screen">
    body {
        color: #ccc;
        background-color: #000;
        font-family: Verdana, Arial, Helvetica, sans-serif;
        font-size: small;
        padding: 20pt;
        padding-top: 0;
    }

    h1,
    h2,
    h3 {
        color: #fff;
    }

    p {
        line-height: 20pt;
    }

    a {
        color: #ffec00;
        text-decoration: none;
    }

    ul {
        margin-bottom: 10px;
        margin-left: 10px;
        padding-left: 10px;
    }

    ul ul {
        margin-top: 6px;
        margin-left: 10px;
    }

    ul li {
        margin-bottom: 10pt;
        list-style-type: none;
        color: #999;
        font-size: 11px;
    }

    ul li li {
        margin-bottom: 0;
    }

    ul li a {
        font-weight: bold;
        font-size: 14px;
    }

    ul li ul li a {
        font-weight: normal;
        color: #ccc;
        font-size: 12px;
    }

    a:hover {
        text-decoration: underline;
    }

    img {
        margin: 8px;
        background: #000000;
        vertical-align: middle;
    }

    li img,
    p img {
        margin-left: 0;
    }
    </style>
</head>

<body>
    <a name="top"></a>
    <div style="text-align: center;">
        <img src="/images/physics-puzzle-maker.png" width="348" height="172" />
    </div>
    <h1>User Guide</h1>

    <p>Welcome to the Sploder Revival Physics Puzzle game maker guide. This guide will help you to understand how to use
        the game maker, and get you started making games.</p>
    <ul>
        <li><a href="#t1">Introduction</a></li>
        <li><a href="#t2">Games &amp; Game Levels</a>
            <ul>
                <li><a href="#t2s1">Testing, Saving &amp; Publishing Games</a></li>
                <li><a href="#t2s2">Adding &amp; Managing Game Levels</a></li>
            </ul>
        </li>
        <li><a href="#t3">Creating Objects</a>
            <ul>
                <li><a href="#t3s1">Prefabs</a></li>
                <li><a href="#t3s2">Drawing New Objects</a></li>
            </ul>
        </li>
        <li><a href="#t4">Selecting Objects</a></li>
        <li><a href="#t5">Changing Object Physics</a>
            <ul>
                <li><a href="#t5s1">Movement</a></li>
                <li><a href="#t5s2">Material</a></li>
                <li><a href="#t5s3">Strength</a></li>
            </ul>
        </li>
        <li><a href="#t6">Changing what Objects Look Like</a></li>
        <li><a href="#t7">Playfield, Background &amp; Goals</a>
            <ul>
                <li><a href="#t7s1">Size, World Physics &amp; Boundaries</a></li>
                <li><a href="#t7s2">Background Colors &amp; Effect</a></li>
                <li><a href="#t7s3">How to Win: Score, Lives &amp; Time Limits</a></li>
            </ul>
        </li>
        <li><a href="#t8">Changing Object Behavior</a>
            <ul>
                <li><a href="#t8s1">Physics: Motors, Joints &amp; Springs</a></li>
                <li><a href="#t8s2">Controls: Adding Keyboard &amp; Mouse Control</a></li>
                <li><a href="#t8s3">Widgets: Spawners, Factories &amp; Connectors</a></li>
            </ul>
        </li>
        <li><a href="#t9">Complex Behaviors</a>
            <ul>
                <li><a href="#t9s1">Locking Objects</a></li>
                <li><a href="#t9s2">Collision, Passthru &amp; Sensor Layers</a></li>
                <li><a href="#t9s3">Object Actions &amp; Events</a></li>
            </ul>
        </li>
        <li><a href="#t10">Sharing &amp; Copying Games</a></li>
        <li><a href="#t11">Further Help</a></li>

    </ul>

    <a name="t1"></a>
    <br />
    <h2>Introduction</h2>



    <p><img src="/images/help/interface.png" width="361" height="296" align="middle" /><br />
        The Physics Puzzle Maker allows you to create small worlds that simulate realistic physics. You can create
        worlds with objects you can move, aim and drag around. You can add projectiles, crushable objects, and connect
        objects together with joints and springs. You can add scoring and penalties to your simulations to turn them
        into games or puzzles. Since this is a Sploder Revival game maker, you can publish and share your games on the
        sploder revival web site, just like the other creators.</p>

    <h3>Help as you Create</h3>

    <p>Almost every button in the creator will show more information to help you if you hold your mouse over the button.
        Also, there is a prompt at the bottom of the creator that will give you further help as you create your world.
        Be sure to take the tour the first time you use the creator, as it will give you a general overview of the parts
        of the interface.</p>

    <h3>Different Modes</h3>

    <p>Certain buttons and selections only apply to specific tools, or your current selection. So, the right side of the
        toolbar will change depending on which tool you've selected, and whether or not you have selected any objects.
    </p>

    <p><a href="#top">Back to Top</a></p>

    <a name="t2"></a>
    <br />
    <h2>Games &amp; Game Levels</h2>

    <p>At the top left of the creator you will see the standard game management tools, which are <strong>New, Load,
            Save, Save As, Test &amp; Publish</strong>. There is also a Level Selector at the top right corner.</p>

    <p>Physics puzzles and game levels do not carry game settings from level to level. For instance, if you have 2 lives
        and 100 score in the first level, it does not carry to the next level. Each level starts fresh, but you must win
        a level to proceed to the next level.</p>

    <a name="t2s1"></a>
    <br />
    <h3>Testing, Saving &amp; Publishing Games</h3>
    <p><img src="/images/help/game_manager.png" width="475" height="40" /><br />At any time, you can test your game by
        pressing the <strong>Test</strong> button. This will test your current level. Be sure to save your game early
        and often as you build it. Saving is not the same as publishing, so you can save as often as you wish, and then
        publish when you are ready to share your creation with the world.</p>

    <p><a href="#top">Back to Top</a></p>


    <a name="t2s2"></a>
    <br />
    <h3>Adding &amp; Managing Game Levels</h3>
    <p><img src="/images/help/level_manager.png" width="204" height="79" align="left" />You can add up to 9 levels to
        your game. Levels are played in order from 1 to 9. To add a level, press the <strong>+</strong> button. You can
        change the order of levels by selecting a later level and pressing the <strong>&#x25B2;</strong> button. To
        remove a level, press the <strong>-</strong> button.</p>
    <p><a href="#top">Back to Top</a></p>


    <a name="t3"></a>
    <br />
    <h2>Creating Objects</h2>
    <p>To get started making your world, you'll need to add some objects. There are two ways to do this. You can start
        by adding ready-made objects, called Prefabs. You can also draw your own objects using the drawing tool.</p>

    <a name="t3s1"></a>
    <br />
    <h3>Prefabs</h3>
    <p><img src="/images/help/prefabs.png" width="123" height="211" align="left" />Perhaps the simplest way to add
        complex objects to your game is to drag and drop ready-made objects from the Prefab tray onto the canvas. These
        objects already have all of the settings necessary to be used in your game. These objects were created with the
        creator itself, so you can modify them or pull them apart to learn how they work. Once you learn how these are
        made, you can use your knowledge to make similar objects.</p>


    <a name="t3s2"></a>
    <br style="clear: both;" />
    <h3>Drawing New Objects</h3>
    <p><img src="/images/help/tool_draw.png" width="219" height="44" align="left" />To make new objects in your game
        from scratch, you start with the drawing tool. The drawing tool is the first button on the toolbar. When you
        select that tool, you'll see options on the right that allow you to select what to draw. The first dropdown menu
        shows a list of shapes you can draw, like circle, square, ramp and polygon. Drawing starts by clicking and
        dragging on the canvas. The polygon drawing is a little different - you draw just by sketching out the shape.
    </p>
    <p>Once you create a new object, you can use the select tool (the second tool on the toolbar) to edit the shape.
        When you select an object, you will see yellow handles that appear. You can click and drag those handles to
        change the shape. For polygons, you can move individual points. Double click on the points to remove them, and
        double click on a polygon edge to add a point.</p>
    <p><a href="#top">Back to Top</a></p>


    <a name="t4"></a>
    <br />
    <h2>Selecting Objects</h2>
    <p><img src="/images/help/tool_select.png" width="218" height="43" align="left" />There are two ways to select
        objects. The Select tool allows you to select a single object. You can edit its shape, size and rotation by
        dragging the handles that appear. You can also edit any modifiers that are attached to it. <img
            src="/images/help/handles.png" width="168" height="164" align="right" />The Window Select tool allows you to
        select multiple object by dragging a selection window. It also hides all modifiers so you can edit objects
        without the modifiers getting in your way.</p>

    <p>When you select objects, you will see the attributes tools appear to the right of the main tools. You can edit
        the properties of selected objects as a group. You cannot change the shape of objects by selecting a new shape
        from the dropdown. Once a shape is created, it is always that shape.</p>
    <p>You can delete objects by pressing the Delete key on your keyboard, or by pressing the <img
            src="/images/help/help0002.gif" width="19" height="19" />at the upper right corner. If you've selected
        multiple objects, you can also group them together for convenient editing by clicking the <img
            src="/images/help/help0011.gif" width="18" height="18" />under the Delete button.</p>
    <p>You can also use several standard keyboard shortcuts with selected objects.</p>
    <ul>
        <li><strong>CTRL-A</strong>: Select All</li>
        <li><strong>CTRL-C</strong>: Copy selection</li>
        <li><strong>CTRL-X</strong>: Cut selection</li>
        <li><strong>CTRL-V</strong>: Paste previously copied or cut selection</li>
        <li><strong>&larr; &uarr; &rarr; &darr;</strong>: Move selection</li>
    </ul>
    <p><a href="#top">Back to Top</a></p>


    <a name="t5"></a>
    <br />
    <h2>Changing Object Physics</h2>
    <img src="/images/help/attributes.png" width="409" height="45" />
    <p>All objects you create have physical properties, which affect how they behave in the game.</p>

    <a name="t5s1"></a>
    <br />
    <h3>Movement</h3>
    <p>Next to the Shape menu is the Movement Constraints menu. With it, you can select different kinds of movement for
        your object.</p>
    <ul>
        <li><img src="/images/help/help0006.gif" width="42" height="42" /><strong>Free:</strong> Object moves and
            rotates naturally with no constraints.</li>
        <li><img src="/images/help/help0007.gif" width="42" height="42" /><strong>Pinned:</strong> Object rotates but
            does not move, like it is pinned.</li>
        <li><img src="/images/help/help0008.gif" width="42" height="42" /><strong>Slide:</strong> Object moves but does
            not rotate, as if it is sliding.</li>
        <li><img src="/images/help/help0009.gif" width="42" height="42" /><strong>Static:</strong> Object does not move
            or rotate, ever.</li>
    </ul>
    <p><img src="/images/help/help0010.gif" width="22" height="22" align="left" /> The lock button will allow you to
        prevent movement of a free, pinned, or sliding object. You can use other actions to unlock the object during the
        simulation. This is covered in more detail later on.</p>


    <a name="t5s2"></a>
    <br />
    <h3>Material</h3>
    <p><img src="/images/help/help0005.gif" width="145" height="144" align="left" /> Object materials affect how much
        objects bounce against eachother or slow down when they rub against eachother (friction.) They also change the
        mass of objects, so more massive objects are &quot;heavier&quot; when there is gravity, and push harder against
        less dense objects. There are also special materials.</p>
    <ul>
        <li>Air (cloud) which is not affected by gravity.</li>
        <li>Magnet (horseshoe magnet), which attracts metal objects.</li>
        <li>Helium (balloon), which travels upward due to bouyancy.</li>
        <li>Superbouncy (pink), which bounces very strongly against other materials.</li>
    </ul>

    <a name="t5s3"></a>
    <br />
    <h3>Strength</h3>
    <p><img src="/images/help/help0013.gif" width="69" height="89" align="left" /> Object strength allows you to create
        objects that are crushed by collision or pressure forces in the game. The default choice is <em>permanent</em>,
        which means it will never shatter. The next three are <em>strong, medium, and weak,</em> which are more and more
        fragile, respectively.</p>
    <p><a href="#top">Back to Top</a></p>


    <a name="t6"></a>
    <br />
    <h2>Changing what Objects Look Like</h2>
    <img src="/images/help/tool_paint.png" width="218" height="44" />
    <p>You can change the look and feel of objects in the game using the paint tool. Simply double-click on any object
        to pain it with the current styles. The pick tool, next to the paint tool, allows you to pick up styles from
        other objects so you can paint with them. You can also select multiple objects, just like the Window Select
        tool, and modify them while in paint mode.</p>
    <img src="/images/help/styles.png" width="339" height="45" />
    <p>You can change several styles for any object. You can change the fill color, line color, or texture. You can also
        choose to turn off fills, lines, or textures for any object. Finally, you can change the layering of the object
        to choose which objects appear on top of others when they overlap. The last two buttons are Opacity, which
        allows you to make objects see-through, and Scribble, which allows you to make an object appear hand-drawn.</p>
    <p><a href="#top">Back to Top</a></p>


    <a name="t7"></a>
    <br />
    <h2>Playfield, Background &amp; Goals</h2>
    <p><img src="/images/help/world_settings.png" width="485" height="44" /><br />
        If you activate the select tool and have no objects selected, you will see buttons for changing general settings
        for your game level.</p>

    <a name="t7s1"></a>
    <br />
    <h3>Size, World Physics &amp; Boundaries</h3>
    <p>The Playfield button allows you to set up the physical properties for the world, and the way it appears. You can
        set up a larger game area, turn gravity off or on, and choose the world boundaries.</p>

    <a name="t7s2"></a>
    <br />
    <h3>Background Colors &amp; Effect</h3>
    <p>The Background button allows you to change the background colors for your level. You can also add effects like
        rain, snow, or clouds.</p>

    <a name="t7s3"></a>
    <br />
    <h3>How to Win: Score, Lives &amp; Time Limits</h3>
    <p>The Goals button allows you to set up the rules for the game level. You can set up how many lives you have at the
        start of the game, how many points you need to score to win, how many penalties you can take before you lose a
        life, and whether or not there is a time limit.</p>
    <p><a href="#top">Back to Top</a></p>


    <a name="t8"></a>
    <br />
    <h2>Changing Object Behavior</h2>
    <p><img src="/images/help/trays.png" width="104" height="142" align="left" />On the left side of the creator are
        three more trays that are hidden when the creator starts. Click on any of the tabs to view them. They contain
        <em>modifiers</em> that allow you to change the way objects behave in the game. These can be dragged onto
        existing objects and modified by dragging the ends of the modifiers. Modifiers can only be edited in Select
        mode. In draw mode and Window Select mode, the modifiers are not selectable. This allows you to edit and move
        objects without the modifiers getting in the way.
    </p>


    <a name="t8s1"></a>
    <br />
    <h3>Physics: Motors, Joints &amp; Springs</h3>
    <p>The Physics panel contains modifiers that can be used to change the physics behavir of objects. Springs and
        joints can be used to connect objects, or to pin objects to the background. PinJoints can be used to connect
        objects with a stiff bar. If you drag the objects together so the ends meet, you can create a Bolt joint. Bolt
        joints can be used to create complex moving objects like the Robot prefab.</p>
    <p><a href="#top">Back to Top</a></p>


    <a name="t8s2"></a>
    <br />
    <h3>Controls: Adding Keyboard &amp; Mouse Control</h3>
    <p>The Controls panel allows you to make objects controllable with the keyboard and/or mouse. This is useful for
        creating &quot;Player&quot; objects or physics puzzles you can manipulate. The Selector modifier allows you to
        focus keyboard and mouse control on a single object by selecting it with the mouse. This way, you can create
        multiple controllable objects, and control them one at a time. The Adder modifier allows to to create an object
        that duplicates itself with the press of the spacebar or mouse button.</p>
    <p><a href="#top">Back to Top</a></p>


    <a name="t8s3"></a>
    <br />
    <h3>Widgets: Spawners, Factories &amp; Connectors</h3>
    <p>The Widgets panel allows you to add modifiers that create complex active objects that are controlled by the
        computer. The Spawner modifier is just like the Adder, except it creates duplicates automatically. The Connect
        modifier is special - it allows you to attach two objects in a parent-child relationship, so you can attach a
        Spawner or Adder to a moving object. The EventLink modifier allows you to connect the events of one object to
        another object. Factories allow you to create a Spawner that duplicates a set of objects, like a Robot, so you
        can have complex objects duplicated during the simulation.</p>
    <p><a href="#top">Back to Top</a></p>


    <a name="t9"></a>
    <br />
    <h2>Complex Behaviors</h2>
    <a name="t9s1"></a>

    <h3>Locking Objects</h3>
    <p><img src="/images/help/help0010.gif" width="22" height="22" align="left" />When you lock an object in the
        creator, it not only locks its motion, it locks all modifiers attached to the object. So, you can start the
        object in an inactive state, and then attach an unlock action to it to turn it on in response to an event.</p>
    <p><a href="#top">Back to Top</a></p>


    <a name="t10s2"></a>
    <br />
    <h3>Collision, Passthru &amp; Sensor Layers</h3>
    <p><img src="/images/help/layers.png" width="174" height="211" align="left" /> With the <img
            src="/images/help/help0014.gif" width="21" height="19" /> button you can set up three physics layers for
        your object. Collision layers control which objects collide. Objects on the same collision layer will always
        collide. Passthru layers allow you to set up groups of objects you want to never collide. This is useful for
        objects that have sensor events that you don't wish to be activated when they collide with eachother. Finally,
        objects on the same Sensor layer will trigger a Sensor event for both objects.</p>
    <p><a href="#top">Back to Top</a></p>


    <a name="t9s3"></a>
    <br />
    <h3>Object Actions &amp; Events</h3>
    <p>If you select an object and click the <img src="/images/help/help0001.gif" width="12" height="18" /> button, you
        will be able to turn your objects into special game objects that do seemingly intelligent things like increase
        the score when they are touched by the player. The Object Actions dialogue allows you to assign Actions to
        Events that happen to the object.</p>

    <img src="/images/help/actionmatrix.png" width="400" height="245" />

    <p>Events are things that happen to the object during the simulation. When you think of Events, replace it in your
        mind with the words &quot;When this happens...&quot;</p>
    <ul>
        <li><strong>Sensor:</strong> An object on the same sensor layer touches this object. A sensor event between the
            same two objects pccurs only once in the game.</li>
        <li><strong>Crush:</strong> This object is crushed. It must have a non-permanent strength setting for the
            object.</li>
        <li><strong>Empty:</strong> This object has an Adder, Spawner or Factory Modifier, and it is finished making all
            of its duplicates.</li>
        <li><strong>Out of Bounds:</strong> This object falls out of the game area (if your game is not enclosed.)</li>
    </ul>

    <p><a href="#top">Back to Top</a></p>


    <a name="t10"></a>
    <br />
    <h2>Sharing &amp; Copying Games</h2>
    <p><img src="/images/help/copythis.png" width="161" height="72" align="left" />If you or anyone else has published
        their game and allowed copying, then you can copy the game level by clicking the <strong>Copy This</strong>
        button at the bottom right corner of the game. Once you come to the creator, click the Select tool, then the
        Clipboard button, and paste the level into your clipboard. You can then modify the game, learn from it, and make
        it your own!</p>


    <a name="t11"></a>
    <br />
    <h2>Further Help</a></h2>
    <p>That's it! There is a lot more to this creator once you get used to the way it works. If you have questions, the
        best place to ask is in the <a href="https://discord.com/invite/<?= getenv('DISCORD_INVITE') ?>/"
            target="_blank">Discord server</a>.
    </p>
    <p><a href="#top">Back to Top</a></p>

</body>

</html>