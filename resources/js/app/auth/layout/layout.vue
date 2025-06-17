<template>
    <div class="authentication-background relative">
        <div class="bg-cover inset-0 absolute object-cover w-full h-screen opacity-10 -z-1" :style="{ backgroundImage: 'Url(/images/authentication-background.svg)' }"></div>
        <div class="snow-container" id="snow-container"></div>
        <section class="relative w-full h-[100vh] grid overflow-y-auto justify-center p-10 items-center relative z-50">
            <div class="w-full sm:min-w-[420px] sm:max-w-[420px] h-auto bg-white p-10 rounded-xl shadow-lg">
                <router-view/>
            </div>
        </section>
    </div>
</template>

<script>

export default {
    data() {
        return {}
    },
    mounted() {
        const snowContainer = document.getElementById('snow-container');

        function createSnowflake() {
            const snowflake = document.createElement('div');
            snowflake.classList.add('snowflake');

            const size = Math.random() * 7 + 4;
            snowflake.style.width = `${size}px`;
            snowflake.style.height = `${size}px`;

            const left = Math.random() * window.innerWidth;
            snowflake.style.left = `${left}px`;
            snowflake.style.top = `-${size}px`;

            const colors = [
                'rgba(3,181,98,0.8)',
                'rgba(0,150,136,0.7)',
                'rgba(255,255,255,0.5)',
                'rgba(0,255,200,0.4)'
            ];
            snowflake.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];

            const duration = Math.random() * 6 + 2;
            const animations = ['fall', 'diagonal-fall', 'spin-fall'];
            const chosenAnim = animations[Math.floor(Math.random() * animations.length)];
            snowflake.style.animationName = chosenAnim;
            snowflake.style.animationDuration = `${duration}s`;
            snowflake.style.animationTimingFunction = 'linear';

            // Track and remove snowflake manually
            const observer = () => {
                const rect = snowflake.getBoundingClientRect();
                if (rect.top > window.innerHeight) {
                    snowflake.remove();
                } else {
                    requestAnimationFrame(observer);
                }
            };
            requestAnimationFrame(observer);

            snowContainer.appendChild(snowflake);
        }

        setInterval(createSnowflake, 40);
    },
}

</script>
