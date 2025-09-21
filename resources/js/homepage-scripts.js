        //Image carousel script
        $(document).ready(function() {
            let currentSlide = 0;
            const totalSlides = $('.carousel-slide').length;
            const slideInterval = 4000; // 4 seconds
            
            function updateCarousel() {
                const translateX = -currentSlide * 100;
                $('.carousel-container').css('transform', `translateX(${translateX}%)`);
                
                // Update indicators
                $('.carousel-indicator').removeClass('bg-white').addClass('bg-white bg-opacity-50');
                $(`.carousel-indicator[data-slide="${currentSlide}"]`).removeClass('bg-opacity-50').addClass('bg-opacity-100');
            }
            
            function nextSlide() {
                currentSlide = (currentSlide + 1) % totalSlides;
                updateCarousel();
            }
            
            // Auto-advance slides
            let autoSlide = setInterval(nextSlide, slideInterval);
            
            // Handle indicator clicks
            $('.carousel-indicator').click(function() {
                currentSlide = parseInt($(this).data('slide'));
                updateCarousel();
                
                // Reset auto-advance timer
                clearInterval(autoSlide);
                autoSlide = setInterval(nextSlide, slideInterval);
            });
            
            // Pause on hover
            $('.carousel-container').hover(
                function() {
                    clearInterval(autoSlide);
                },
                function() {
                    autoSlide = setInterval(nextSlide, slideInterval);
                }
            );
            
            // Initialize first slide
            updateCarousel();
        });