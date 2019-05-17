<?php /*
    Objectives Here:
        Redesign.  Keep the same general Melee Trophy Aesthetic, but change some things around, such as:
            Move the character swap arrows to the same area as the image
            Various Responsiveness issues, but especially change font size at certain screen sizes
            [ADD MORE HERE]
        Improve the performance.  This primarily means dont load the entire sidebar at once
        Improve the navigation experience.  It takes too long to scroll through all 1300 spirits in the sidebar, what are some better ways to speed up this process?  Possibly add a searchbar?
        Add a Random Spirit button
*/ ?>
<?php
    include_once 'connection/connect.php';
    if ($_GET['id'] == 0) {
        $id = rand(1, 1320);
    } else {
        $id = $_GET['id'];
    }
    
    $sql = "SELECT * FROM spirits WHERE id='$id'";
    $result = $conn->query($sql);
    if($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $sid = $row['id'];
            $name = $row['name'];
            $game = $row['game'];
            $series = $row['series'];
            $description = $row['description'];
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="style/details/index.css" />
    <link rel="stylesheet" href="./templates/external_scripts/nouislider.min.css" />
    <title><?php echo $name; ?> | Details</title>
</head>
<?php
    echo "<script>let currentID=$id;</script>";
?>
<body>
    <nav>
        <div onClick="getSpirit('previous', currentID)" class="navArrow" id="previousSpirit"><-- Previous Spirit</div>
        <a id="indexLink" href="index.php?place=<?php echo $id; ?>" class="navLink"> Return to Index </a>
        <div onClick="getSpirit('random', 0)" class="navLink"> Random Spirit </a></div>
        <div onClick="getSpirit('next', currentID)" class="navArrow" id="nextSpirit">Next Spirit --></div>
    </nav>
    <div class="descSection" style="width: 50vw; margin-left: 20%;">
            <div class="searchHead">
                <form id="searchSettings">
                    <input type="radio" name="sort" value="id">ID 
                    <input type="radio" name="sort" value="name">Name
                    <input type="radio" name="sort" value="game">Game
                    <input type="radio" name="sort" value="series">Series
                    <input type="radio" name="sort" value="release_year"> Release Year
                    <br />
                    <input type="checkbox" name="series" value="AnimalCrossing"> Animal Crossing
                    <input type="checkbox" name="series" value="Bayonetta"> Bayonetta
                    <input type="checkbox" name="series" value="Castlevania"> Castlevania
                    <input type="checkbox" name="series" value="DK"> Donkey Kong
                    <input type="checkbox" name="series" value="DuckHunt"> Duck Hunt
                    <input type="checkbox" name="series" value="FinalFantasy"> Final Fantasy
                    <input type="checkbox" name="series" value="FireEmblem"> Fire Emblem
                    <input type="checkbox" name="series" value="FZero"> F-Zero
                    <input type="checkbox" name="series" value="GameWatch"> Game & Watch
                    <input type="checkbox" name="series" value="IceClimber"> Ice Climber
                    <input type="checkbox" name="series" value="KidIcarus"> Kid Icarus
                    <input type="checkbox" name="series" value="Kirby"> Kirby
                    <input type="checkbox" name="series" value="Mario"> Mario
                    <input type="checkbox" name="series" value="MegaMan"> Mega Man
                    <input type="checkbox" name="series" value="MetalGear"> Metal Gear
                    <input type="checkbox" name="series" value="Metroid"> Metroid
                    <input type="checkbox" name="series" value="Mii"> Mii
                    <input type="checkbox" name="series" value="Mother"> Mother
                    <input type="checkbox" name="series" value="Other"> Other
                    <input type="checkbox" name="series" value="PacMan"> Pac-Man
                    <input type="checkbox" name="series" value="Persona"> Persona
                    <input type="checkbox" name="series" value="Pikmin"> Pikmin
                    <input type="checkbox" name="series" value="Pokemon"> Pokemon
                    <input type="checkbox" name="series" value="PunchOut"> Punch Out
                    <input type="checkbox" name="series" value="ROB"> R.O.B.
                    <input type="checkbox" name="series" value="Smash"> Super Smash Brothers
                    <input type="checkbox" name="series" value="Sonic"> Sonic
                    <input type="checkbox" name="series" value="Splatoon"> Splatoon
                    <input type="checkbox" name="series" value="StarFox"> Star Fox
                    <input type="checkbox" name="series" value="StreetFighter"> Street Fighter
                    <input type="checkbox" name="series" value="Wario"> Wario
                    <input type="checkbox" name="series" value="WiiFit"> Wii Fit
                    <input type="checkbox" name="series" value="Xenoblade"> Xenoblade
                    <input type="checkbox" name="series" value="Yoshi"> Yoshi
                    <input type="checkbox" name="series" value="Zelda"> Zelda 
                    <br />
                    <div id="yearSlider"> </div>
                    <div onClick="handleSidebarSpiritInput()" style="background-color: white; border-radius: 10px; border: 1px solid black; color: black;">Test Sorting API</div>
                </form>
            </div>
        </div>
        <div class="descSection">
            <div id="searchBody" class="searchBody">
                
            </div>
        </div>
    <div class="descBody" id="descBody">
        
        
        <div class="descSection">
            <div class="descImgContainer">
                <img src="img/spiritImages/<?php echo $sid; ?>.png" alt="<?php echo $name; ?>" />
            </div>
        </div>
        <div class="descSection">
            <h2><?php echo $id . " ". $name; ?></h2>
            <div class="descBox primary">
                <p class="descText"><?php echo $description; ?></p>
            </div>
            <div class="descBox secondary">
                <img src="img/seriesIcons/<?php echo $series; ?>.png" alt="<?php echo $series;?>" />
                <p class="descGameText"><?php echo $game; ?> </p>
            </div>
        </div>
    </div>
</body>
<script src="./templates/external_scripts/nouislider.min.js"></script>
<script>
    //this constant needs to be updated whenever there is a new total amount of spirits
    const max = 1320;
    function getNextSpirit(id) {
        if(id == max) {
            nextID = 1;
        } else {
            nextID = id + 1;
        }
        let url = `api/spirits/getOne.php?id=${nextID}`;
        let options = {
            method: "GET",
            mode: "cors",
            cache: "no-cache",
            credentials: "same-origin",
            headers: {
                "Content-Type": "application/json"
            },
            redirect: "follow",
            referrer: "no-referrer"
        };
        return fetch(url, options)
            .then(response => response.json())
            .then(jsonresponse => {
                let sid = Number(jsonresponse.records[0].id);
                let name = jsonresponse.records[0].name;
                responsehtml = `
                    ${sid} ${name} -->
                `;
                document.getElementById('nextSpirit').innerHTML = responsehtml;
            })
            .catch(error => console.error(error));
    }
    function getPreviousSpirit(id) {
        if(id == 1) {
            nextID = max;
        } else {
            nextID = id - 1;
        }
        let url = `api/spirits/getOne.php?id=${nextID}`;
        let options = {
            method: "GET",
            mode: "cors",
            cache: "no-cache",
            credentials: "same-origin",
            headers: {
                "Content-Type": "application/json"
            },
            redirect: "follow",
            referrer: "no-referrer"
        };
        return fetch(url, options)
            .then(response => response.json())
            .then(jsonresponse => {
                let sid = Number(jsonresponse.records[0].id);
                let name = jsonresponse.records[0].name;
                responsehtml = `<-- ${sid} ${name}`;
                document.getElementById('previousSpirit').innerHTML = responsehtml;
            })
            .catch(error => console.error(error));
    }
    window.onload = function() {
        let sid = <?php echo $id; ?>;
        getNextSpirit(sid);
        getPreviousSpirit(sid);
    }
    function getSpirit(action, id) {
        switch(action) {
            case 'next':   
                spiritID = id + 1;
                break;
            case 'previous':
                spiritID = id - 1;
                break;
            case 'random':
                spiritID = Math.floor(Math.random() * max);
                break;
            default:
                spiritID = id;
                break;
        }
        let url = `api/spirits/getOne.php?id=${spiritID}`;
        let options = {
            method: "GET",
            mode: "cors",
            cache: "no-cache",
            credentials: "same-origin",
            headers: {
                "Content-Type": "application/json"
            },
            redirect: "follow",
            referrer: "no-referrer",
        };
        return fetch(url, options)
            .then(response => response.json())
            .then(jsonresponse => {
                console.log(jsonresponse.records);
                let id = Number(jsonresponse.records[0].id);
                let name = jsonresponse.records[0].name;
                let game = jsonresponse.records[0].game;
                let series = jsonresponse.records[0].series;
                let description = jsonresponse.records[0].description;
                htmlresponsecode = `
                <div class="descSection">
                    <div class="descImgContainer">
                        <img src="img/spiritImages/${id}.png" alt="${name}" />
                    </div>
                </div>
                <div class="descSection">
                    <h2>${id} ${name}</h2>
                    <div class="descBox primary">
                        <p class="descText">${description}</p>
                    </div>
                    <div class="descBox secondary">
                        <img src="img/seriesIcons/${series}.png" alt="${series}" />
                        <p class="descGameText">${game}</p>
                    </div>
                </div>
                `;
                document.getElementById('descBody').innerHTML = htmlresponsecode;
                document.title = `${name} | Details`;
                document.getElementById('indexLink').setAttribute('href', `index.php?place=${id}`);
                currentID = id;
                getNextSpirit(spiritID);
                getPreviousSpirit(spiritID);
                history.pushState({}, null, `details.php?id=${spiritID}`)
            })
            .catch(error=> console.error(error));
            
    }
//input: sort is one of ["name", "game", "id", "series", "release_year"]
//       offset is an int between 1 and 1320
//       excludeArr is an array containing which series to include
//       yearRange is an array with 2 values, [minYear, maxYear]
function getSidebarSpirits(sort, offset, includeArr, yearRange) {
    let includeList;
    let sentOffset;
    let sentSort;
    if(includeArr === undefined || includeArr.length == 0) {
        includeList = "all";
    } else {
        includeList = includeArr;
    }
    if(offset > max - 20) {
        sentOffset = max - 20;
    } else if(offset == max) {
        sentOffset = 0;
    } else {
        sentOffset = offset;
    }
    switch (sort) {
        case "name":
            sentSort = "name";
            break;
        case "game":
            sentSort = "game";
            break;
        case "id":
            sentSort = "id";
            break;
        case "series":
            sentSort = "series";
            break;
        case "release_year":
            sentSort = "release_year";
            break;
        default:   
            sentSort = "id";
            break;
    }
    let url = "./api/spirits/detailsSearch.php";
    let options = {
        method: "POST",
        mode: "cors",
        cache: "no-cache",
        credentials: "same-origin",
        headers: {
            "Content-Type": "application/json"
        },
        redirect: "follow",
        referrer: "no-referrer",
        body: JSON.stringify({
            "sortType": sentSort,
            "offset": sentOffset,
            "includes": includeList,
            "minYear": yearRange[0],
            "maxYear": yearRange[1]
        })
    }
    return fetch(url, options)
        .then(response => response.json())
        .then(data => {
            htmlresponsecode = `<div class="spiritsSidebar">`;
            data.map(item => {
                htmlresponsecode = htmlresponsecode + `
                    <div class="spiritsSidebarItem" onClick="getSpirit('default', ${item.id.toString()})">`
                    //<img src="img/seriesIcons/${item.series}.png" />  commenting out til I style this part of the page, since the images massacre the layout as is
                    +`<p>${item.id} ${item.name} </p>
                    </div>
                `;
            });
            htmlresponsecode = htmlresponsecode + `</div>`;
            document.getElementById('searchBody').innerHTML = htmlresponsecode;

        })
        .catch(error => console.error(error));
}
getSidebarSpirits("id",<?php echo $id - 1; ?> ,"all", [1980, 2019]);



    let yearSlider = document.getElementById('yearSlider');
    noUiSlider.create(yearSlider, {
        start: [1980, 2019],
        animate: true,
        animationDuration: 350,
        connect: true,
        step: 1,
        tooltips: true,
        range: {
            'min': 1980,
            'max': 2019
        },
        format: {
            to: function( value ) {
                return value + '';
            },
            from: function( value ) {
                return value.replace(',-', '');
            }
        }
    });

    function handleSidebarSpiritInput() {
        console.log(document.getElementById('searchSettings').elements);
        let sortValue;
        let offsetValue;
        let seriesValue = [];
        let yearRangeValue;
        //handle sort inputs
        let inputs = document.getElementById('searchSettings');
        for(var a = 0; a < 4; a++) {
            if(inputs[a].checked) {
                sortValue = inputs[a].value; 
            }
        }
        //handle offset input
        var parts = window.location.search.substr(1).split("&");
        var $_GET = {};
        for (var i = 0; i < parts.length; i++) {
            var temp = parts[i].split("=");
            $_GET[decodeURIComponent(temp[0])] = decodeURIComponent(temp[1]);
        }
        offsetValue = $_GET.id;
        //handle series input
        for(var b = 4; b < 39; b++) {
            if(inputs[b].checked) {
                seriesValue.push(inputs[b].value);
            }
        }
        //handle year range input
        let minYear = Number(yearSlider.noUiSlider.get()[0]);
        let maxYear = Number(yearSlider.noUiSlider.get()[1]);
        yearRangeValue = [minYear, maxYear];
        return getSidebarSpirits(sortValue, offsetValue, seriesValue, yearRangeValue);
    }


    document.getElementsByClassName("noUi-tooltip").map(item => {
       item.innerHTML = item.innerHTML.slice(0, -3); 
    });
</script>
</html>


