<section class="relative overflow-hidden bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-700 text-white py-8 md:py-12 lg:py-16 xl:py-18">
    <!-- Hiệu ứng hình học nền (giảm bớt) -->
    <div class="absolute inset-0 opacity-5">
        <div class="absolute -top-24 -left-24 w-72 h-72 rounded-full bg-white"></div>
        <div class="absolute bottom-0 right-0 w-64 h-64 rounded-full bg-blue-300"></div>
    </div>
    
    <div class="container mx-auto px-6 sm:px-10 lg:px-16 xl:px-24 relative z-10 max-w-screen-xl">
        <div class="flex flex-col md:flex-row items-center justify-between">
            <!-- Phần Nội dung -->
            <div class="md:w-1/2 mb-6 md:mb-0 text-center md:text-left md:pr-8 lg:pr-12 xl:pr-16">
                <h1 class="text-2xl md:text-3xl lg:text-4xl xl:text-4xl font-bold mb-3 leading-tight tracking-tight text-white">
                    Học tập <span class="text-yellow-300">không giới hạn</span>
                </h1>
                <p class="text-base md:text-lg mb-6 text-blue-100 font-light max-w-xl">
                    Cùng nhau chia sẻ tri thức và phát triển bản thân mỗi ngày trong không gian học tập hiện đại
                </p>
                <div class="flex flex-col sm:flex-row justify-center md:justify-start space-y-3 sm:space-y-0 sm:space-x-6">
                    <a href="{{ route('front.book.index') }}" class="inline-flex items-center justify-center px-5 py-2 lg:px-6 lg:py-3 rounded-lg bg-white text-blue-700 font-semibold hover:bg-opacity-90 transition duration-300 shadow-lg">
                        <span>Đọc sách ngay</span>
                        <i class="fas fa-book ml-2"></i>
                    </a>
                    <a href="{{ route('front.tblogs.index') }}" class="inline-flex items-center justify-center px-5 py-2 lg:px-6 lg:py-3 rounded-lg bg-transparent border-2 border-white text-white font-semibold hover:bg-white hover:text-blue-700 transition duration-300">
                        <span>Cộng đồng</span>
                        <i class="fas fa-users ml-2"></i>
                    </a>
                </div>
            </div>
            
            <!-- Phần Hình ảnh -->
            <div class="md:w-1/2 flex justify-center md:pl-8 lg:pl-12 xl:pl-16">
                <div class="relative w-full max-w-md lg:max-w-lg xl:max-w-xl">
                    <!-- Bỏ hiệu ứng glow -->
                    <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80" 
                         alt="People studying together" 
                         class="relative rounded-lg shadow-xl w-full h-auto max-h-[22rem] xl:max-h-[26rem] object-cover">
                         
                    <!-- Badge nhỏ -->
                    <div class="absolute bottom-4 right-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-xs md:text-sm px-3 py-1 rounded-md font-medium shadow-md">
                        <i class="fas fa-graduation-cap mr-1"></i> Học tập 4.0
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Thay đổi wave effect thành đường thẳng -->
    <div class="absolute bottom-0 left-0 right-0 h-2 bg-white"></div>
</section>