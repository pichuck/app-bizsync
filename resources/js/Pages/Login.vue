<template>
    <section class="page-section login-page">
        <div class="full-width-screen">
            <div class="container-fluid">
                <div class="content-detail">
                    <div class="main-info">
                        <div class="hero-container">
                            <!-- Login form -->
                            <form class="login-form" method="post">
                                <div
                                    class="imgcontainer flex justify-center items-center"
                                >
                                    <img
                                        src="/assets/images/bizsync-text.png"
                                        style="width: 180px"
                                        alt="Avatar"
                                        class="avatar"
                                    />
                                </div>
                                <div class="input-control">
                                    <input
                                        type="text"
                                        placeholder="Enter Username"
                                        name="uname"
                                        required
                                    />
                                    <span class="password-field-show">
                                        <input
                                            type="password"
                                            placeholder="Enter Password"
                                            name="password"
                                            class="password-field"
                                            value=""
                                            required
                                        />
                                    </span>
                                    <label class="label-container"
                                        >Remember me
                                        <input type="checkbox" />
                                        <span class="checkmark"></span>
                                    </label>
                                    <span class="psw"
                                        ><a
                                            href="forgot.html"
                                            class="forgot-btn"
                                            >Forgot password?</a
                                        ></span
                                    >
                                    <div class="login-btns">
                                        <button type="submit">Login</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script></script>
