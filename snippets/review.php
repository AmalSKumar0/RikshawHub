<link rel="stylesheet" href="snippets/review.scss">
<div class="exterior">
    <div class="reviewBox">
        <h1 class="text"><b>LEAVE A <span>REVIEW</span>...</b></h1>
        <form method="get" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
<div class="feedback">
    <label class="angry">
        <input type="radio" value="1" name="feedback" />
        <div>
            <svg class="eye left">
                <use xlink:href="#eye">
            </svg>
            <svg class="eye right">
                <use xlink:href="#eye">
            </svg>
            <svg class="mouth">
                <use xlink:href="#mouth">
            </svg>
        </div>
    </label>
    <label class="sad">
        <input type="radio" value="2" name="feedback" />
        <div>
            <svg class="eye left">
                <use xlink:href="#eye">
            </svg>
            <svg class="eye right">
                <use xlink:href="#eye">
            </svg>
            <svg class="mouth">
                <use xlink:href="#mouth">
            </svg>
        </div>
    </label>
    <label class="ok">
        <input type="radio" value="3" name="feedback" />
        <div></div>
    </label>
    <label class="good">
        <input type="radio" value="4" name="feedback" checked />
        <div>
            <svg class="eye left">
                <use xlink:href="#eye">
            </svg>
            <svg class="eye right">
                <use xlink:href="#eye">
            </svg>
            <svg class="mouth">
                <use xlink:href="#mouth">
            </svg>
        </div>
    </label>
    <label class="happy">
        <input type="radio" value="5" name="feedback" />
        <div>
            <svg class="eye left">
                <use xlink:href="#eye">
            </svg>
            <svg class="eye right">
                <use xlink:href="#eye">
            </svg>
        </div>
    </label>
</div>
        
<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
    <symbol xmlns="http://www.w3.org/2000/svg" viewBox="0 0 7 4" id="eye">
        <path d="M1,1 C1.83333333,2.16666667 2.66666667,2.75 3.5,2.75 C4.33333333,2.75 5.16666667,2.16666667 6,1"></path>
    </symbol>
    <symbol xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 7" id="mouth">
        <path d="M1,5.5 C3.66666667,2.5 6.33333333,1 9,1 C11.6666667,1 14.3333333,2.5 17,5.5"></path>
    </symbol>
</svg>

        <textarea id="review" name="review_text" placeholder="Write your review here..."></textarea>

        <div class="buttons">
            <button type="button" class="back-btn" onclick="history.back()">Go Back</button>
            <button type="submit" name="submitReview" value="true" class="submit-btn">Submit</button>
        </div>
        </form>
</div>
</div>
