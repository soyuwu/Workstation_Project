<div class="carousel">
    <!-- list item -->
    <div class="list">
        <div class="item">
            <img src="https://images.unsplash.com/photo-1518066000714-58c45f1a2c0a?q=80&w=2100&auto=format&fit=crop" alt="Slide 1">
            <div class="content">
                <div class="author">WORKSTATION</div>
                <div class="title">EXPLORE</div>
                <div class="topic">MOON ART</div>
                <div class="des">
                    Khám phá nghệ thuật không gian tuyệt đẹp. Những trải nghiệm thị giác ấn tượng đưa bạn vào một vũ trụ mới mẻ, xóa nhòa ranh giới giữa thực và ảo qua từng khung hình thiên nhiên rộng lớn.
                </div>
                <div class="buttons">
                    <button>SEE MORE</button>
                    <button>SUBSCRIBE</button>
                </div>
            </div>
        </div>
        <div class="item">
            <img src="https://images.unsplash.com/photo-1419242902214-272b3f66ee7a?q=80&w=2100&auto=format&fit=crop" alt="Slide 3">
            <div class="content">
                <div class="author">WORKSTATION</div>
                <div class="title">NATURE</div>
                <div class="topic">LANDSCAPE</div>
                <div class="des">
                    Hòa mình vào thiên nhiên hùng vĩ với những dãy núi trùng điệp chìm trong bầu trời lãng mạn. Một trải nghiệm tĩnh lặng và yên bình giúp bạn nạp lại năng lượng cho những ngày mới.
                </div>
                <div class="buttons">
                    <button>SEE MORE</button>
                    <button>SUBSCRIBE</button>
                </div>
            </div>
        </div>
        <div class="item">
            <img src="https://images.unsplash.com/photo-1464802686167-b939a6910659?q=80&w=2100&auto=format&fit=crop" alt="Slide 4">
            <div class="content">
                <div class="author">WORKSTATION</div>
                <div class="title">ASTRONOMY</div>
                <div class="topic">ECLIPSE</div>
                <div class="des">
                    Chiêm ngưỡng những khoảnh khắc hiếm hoi của các hiện tượng thiên văn kỳ thú. Sự kỳ diệu của tạo hóa được tái hiện sắc nét ngay trước mắt bạn.
                </div>
                <div class="buttons">
                    <button>SEE MORE</button>
                    <button>SUBSCRIBE</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- thumbnail -->
    <div class="thumbnail">
        <!-- Thumbnail items in same order -->
        <div class="item">
            <img src="https://images.unsplash.com/photo-1518066000714-58c45f1a2c0a?q=80&w=500&auto=format&fit=crop" alt="Thumb 1">
            <div class="content">
                <div class="title">Moon Art</div>
                <div class="description">Description</div>
            </div>
        </div>
        <div class="item">
            <img src="https://images.unsplash.com/photo-1419242902214-272b3f66ee7a?q=80&w=500&auto=format&fit=crop" alt="Thumb 3">
            <div class="content">
                <div class="title">Landscape</div>
                <div class="description">Description</div>
            </div>
        </div>
        <div class="item">
            <img src="https://images.unsplash.com/photo-1464802686167-b939a6910659?q=80&w=500&auto=format&fit=crop" alt="Thumb 4">
            <div class="content">
                <div class="title">Eclipse</div>
                <div class="description">Description</div>
            </div>
        </div>
    </div>
    
    <!-- arrows -->
    <div class="arrows">
        <button id="prev"><</button>
        <button id="next">></button>
    </div>
</div>



<script>
    document.addEventListener('DOMContentLoaded', function() {
        let nextDom = document.getElementById('next');
        let prevDom = document.getElementById('prev');
        
        let carouselDom = document.querySelector('.carousel');
        let SliderDom = carouselDom.querySelector('.carousel .list');
        let thumbnailBorderDom = document.querySelector('.carousel .thumbnail');
        let thumbnailItemsDom = thumbnailBorderDom.querySelectorAll('.item');

        // Bỏ thumbnail đầu ra phía sau, để item 2 hiện lên đầu tiên trong hàng chờ
        thumbnailBorderDom.appendChild(thumbnailItemsDom[0]);

        let timeRunning = 800;
        let runTimeOut;

        nextDom.onclick = function(){
            showSlider('next');    
        }

        prevDom.onclick = function(){
            showSlider('prev');    
        }

        function showSlider(type){
            let SliderItemsDom = SliderDom.querySelectorAll('.carousel .list .item');
            let thumbnailItemsDom = document.querySelectorAll('.carousel .thumbnail .item');
            
            if(type === 'next'){
                SliderDom.appendChild(SliderItemsDom[0]);
                thumbnailBorderDom.appendChild(thumbnailItemsDom[0]);
                carouselDom.classList.add('next');
            }else{
                SliderDom.prepend(SliderItemsDom[SliderItemsDom.length - 1]);
                thumbnailBorderDom.prepend(thumbnailItemsDom[thumbnailItemsDom.length - 1]);
                carouselDom.classList.add('prev');
            }

            clearTimeout(runTimeOut);
            runTimeOut = setTimeout(() => {
                carouselDom.classList.remove('next');
                carouselDom.classList.remove('prev');
            }, timeRunning);
        }
    });
</script>
