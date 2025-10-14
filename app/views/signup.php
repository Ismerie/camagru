<div>
  <h1 class="text-lg">Camagru</h1>
  <div class="min-h-screen flex flex-col items-center justify-center">

    <div class="p-4 py-14 w-96 bg-opacity-70 bg-[--color-lightwhite] rounded-lg border-2 border-[#f4f4f2]">
      <h2 class="text-center text-2xl mt-12">Welcome to Camagru</h2>
      <h3 class="text-center text-sm">Begin by creating an account</h3>

      <form class="space-y-2 mt-12">
        <div class="floating-label">
          <input type="text" name="username" required class="peer rounded-3xl p-3" />
          <label>Username</label>
        </div>

        <div class="floating-label">
          <input type="email" name="email" required class="peer rounded-3xl p-3" />
          <label>Email</label>
        </div>

        <div class="floating-label">
          <input type="password" name="password" required class="peer rounded-3xl p-3" />
          <label>Password</label>
        </div>
      </form>

      <button class="mt-12 rounded-3xl bg-orange-400 hover:bg-orange-500 w-full p-3">
        Continue
      </button>
    </div>
  </div>
</div>
