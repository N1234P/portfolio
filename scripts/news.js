let arrowLeft = document.getElementsByClassName("arrowLeft")[0];
        let arrowRight = document.getElementsByClassName("arrowRight")[0];

        arrowLeft.addEventListener("mouseenter", function() {
            let interval = setInterval(function() {
                document.body.scrollLeft -= 10;
            }, 100);

 
            arrowLeft.addEventListener('mouseleave', function() {
                clearInterval(interval);
            });
        }, 10);

      
        arrowRight.addEventListener("mouseenter", function() {
            let interval = setInterval(function() {
                document.body.scrollLeft += 10;
            }, 100);

            arrowRight.addEventListener("mouseleave", function() {
                clearInterval(interval);
            });
        }, 10);