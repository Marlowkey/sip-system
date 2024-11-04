<script setup>

defineProps({
    text: {
        type: String,
        required: true,
    },
});
</script>

<template>
<section
  class="relative flex flex-col items-center justify-center w-full h-full group"
>
  <div
    class="file relative w-32 h-24 cursor-pointer origin-bottom [perspective:1500px] z-50" 
  >
    <div
      class="work-5 bg-amber-600 w-full h-full origin-top rounded-2xl rounded-tl-none group-hover:shadow-[0_20px_40px_rgba(0,0,0,.2)] transition-all ease duration-300 relative after:absolute after:content-[''] after:bottom-[99%] after:left-0 after:w-12 after:h-2 after:bg-amber-600 after:rounded-t-2xl before:absolute before:content-[''] before:-top-[8px] before:left-[55px] before:w-2 before:h-2 before:bg-amber-600 before:[clip-path:polygon(0_35%,0%_100%,50%_100%);]"
    ></div>
    <div
      class="work-4 absolute inset-1 bg-zinc-400 rounded-2xl transition-all ease duration-300 origin-bottom select-none group-hover:[transform:rotateX(-20deg)]"
    ></div>
    <div
      class="work-3 absolute inset-1 bg-zinc-300 rounded-2xl transition-all ease duration-300 origin-bottom group-hover:[transform:rotateX(-30deg)]"
    ></div>
    <div
      class="work-2 absolute inset-1 bg-zinc-200 rounded-2xl transition-all ease duration-300 origin-bottom group-hover:[transform:rotateX(-38deg)]"
    ></div>
    <div
      class="work-1 absolute bottom-0 bg-gradient-to-t from-amber-500 to-amber-400 w-full h-[80px] rounded-2xl rounded-tr-none after:absolute after:content-[''] after:bottom-[99%] after:right-0 after:w-[80px] after:h-[8px] after:bg-amber-400 after:rounded-t-2xl before:absolute before:content-[''] before:-top-[8px] before:right-[78px] before:size-3 before:bg-amber-400 before:[clip-path:polygon(100%_14%,50%_100%,100%_100%);] transition-all ease duration-300 origin-bottom flex items-end group-hover:shadow-[inset_0_20px_40px_#fbbf24,_inset_0_-20px_40px_#d97706] group-hover:[transform:rotateX(-46deg)_translateY(1px)]"
    ></div>
  </div>
  <p class="pt-1 text-xl opacity-20">{{ text }}</p> <!-- Further reduced text size -->
</section>

</template>

