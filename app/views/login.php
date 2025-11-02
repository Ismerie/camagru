<div>
  <a href="/" class="hidden sm:absolute sm:block text-xl left-5 top-5">
    <p>Camagru</p>
  </a>
  <a href="/" class="sm:hidden absolute z-20 text-lg left-5 top-5">
    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
      <path d="M15 4L7 12L15 20" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
    </svg>
  </a>
  <div class="sm:min-h-screen flex flex-col sm:items-center sm:justify-center">
    <div id="loginForm" class="min-h-screen flex flex-col items-center sm:justify-center">
      <div class="relative z-10 px-10 sm:py-14 sm:bg-[--color-lightwhite] sm:rounded-lg sm:border-2 sm:border-[#f4f4f2]">
        <div class="mb-12 sm:mb-24 mt-12">
          <h2 class="text-center text-2xl">Log In</h2>
          <a href="/signup" class="text-sm flex justify-self-center">
            or&nbsp<span class="underline">create an account</span>
          </a>
        </div>

        <form class="space-y-2 mt-12 w-96 mb-12">
          <div class="floating-label">
            <input type="text" name="username" required class="peer rounded-3xl p-3" autofocus/>
            <label>Username</label>
          </div>

          <div class="floating-label relative">
            <input id="password" type="password" name="password" required class="peer rounded-3xl p-3" />
            <button type="button" id="togglePassword" class="absolute z-20 inset-y-0 right-0 pr-3 flex items-center text-gray-700">
              <svg 
                id="eye-off"
                class="block w-6 h-6 text-gray-700"
                viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M9.76404 5.29519C10.4664 5.10724 11.2123 5 12 5C15.7574 5 18.564 7.4404 20.2326 9.43934C21.4848 10.9394 21.4846 13.0609 20.2324 14.5609C20.0406 14.7907 19.8337 15.0264 19.612 15.2635M12.5 9.04148C13.7563 9.25224 14.7478 10.2437 14.9585 11.5M3 3L21 21M11.5 14.9585C10.4158 14.7766 9.52884 14.0132 9.17072 13M4.34914 8.77822C4.14213 9.00124 3.94821 9.22274 3.76762 9.43907C2.51542 10.9391 2.51523 13.0606 3.76739 14.5607C5.43604 16.5596 8.24263 19 12 19C12.8021 19 13.5608 18.8888 14.2744 18.6944" stroke="#7A7A7A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
                <svg 
                  id="eye"
                  class="hidden w-6 h-6"
                  viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M12 5C8.24261 5 5.43602 7.4404 3.76737 9.43934C2.51521 10.9394 2.51521 13.0606 3.76737 14.5607C5.43602 16.5596 8.24261 19 12 19C15.7574 19 18.564 16.5596 20.2326 14.5607C21.4848 13.0606 21.4848 10.9394 20.2326 9.43934C18.564 7.4404 15.7574 5 12 5Z" stroke="#7A7A7A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M12 15C13.6569 15 15 13.6569 15 12C15 10.3431 13.6569 9 12 9C10.3431 9 9 10.3431 9 12C9 13.6569 10.3431 15 12 15Z" stroke="#7A7A7A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
            </button>
            <label>Password</label>
          </div>
        </form>
        <button id="submit" class="mt-12 rounded-3xl bg-[--color-lightbrown] hover:bg-[--color-brown] w-full p-3">
          Enter
        </button>
        <a href="/reset-password" class="hover:underline text-sm flex justify-self-center mt-3">Forgot password ?</a>
      </div>
    </div>
  </div>
</div>