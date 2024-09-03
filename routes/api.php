<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminBorrowController;
use App\Http\Controllers\Api\BookFilterController;
;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group.
|
*/

/**The first api is the login api route that will take the path of the /api/login and 
 *  @param of the authcontroller to check the user creduntials with the @method login inside the Controller
 **/

Route::post('/login', [AuthController::class, 'login']);
/**This api is the register api route that will take the path of the /api/register and 
 *  @param of the authcontroller to check the make user with the @method register inside the Controller
 **/
Route::post('/register', [AuthController::class, 'register']);
/**This api is the Books api route that will take the path of the /api/books and 
 *  @param of the BookController  @method index inside the Controller to list all of the books found in the system
 **/
Route::get('/books', [BookController::class, 'index']);
/**Inside this group only the registered users that had the type admin could get the request to 
 * make crud operations on the user model using the AdminController as the action for the request
 * @param index for listing all the users in the admin area
 * @param store to make a user in the admin area
 * @show to show a user attributes 
 * @update for updating an attribute for the user
 * @destroy for deleting the user with response of @return of null
 * 
 */

 Route::middleware(['auth:api', 'isAdmin'])->group(function () {
    Route::get('/admin/users', [AdminController::class, 'index']);
    Route::post('/admin/users', [AdminController::class, 'store']);
    Route::get('/admin/users/{id}', [AdminController::class, 'show']);
    Route::put('/admin/users/{id}', [AdminController::class, 'update']);
    Route::delete('/admin/users/{id}', [AdminController::class, 'destroy']);
    Route::post('/logout', [AuthController::class, 'logout']);
    //here the admin could see all the borrows and get specific borrow and edit a borrow or delete it 
    Route::get('/admin/borrows', [AdminBorrowController::class, 'index']);
    Route::get('/admin/borrows/{id}', [AdminBorrowController::class, 'show']);
    Route::put('/admin/borrows/{id}', [AdminBorrowController::class, 'update']);
    Route::delete('/admin/borrows/{id}', [AdminBorrowController::class, 'destroy']);

});



// Routes that require authentication

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // Books routes
    Route::post('/books', [BookController::class, 'store']);
    Route::get('/books/{id}', [BookController::class, 'show']);
    Route::put('/books/{id}', [BookController::class, 'update']);
    Route::delete('/books/{id}', [BookController::class, 'destroy']);

    // Borrow routes
    /**In this borrows route i had divided the main idea of borrow to be 
     * uri for the normal user so he could create and store and update the attribut's borrow that he had created
     * with the ability of listing all of th borrows that he had make 
     * 
     * Admin area so he could list all of the users borrows ,show a single borrow, update a single one or delete it 
     */     
    Route::post('/borrows', [BorrowController::class, 'store']);
    Route::get('/borrows', [BorrowController::class, 'index']);
    Route::get('/borrows/{id}', [BorrowController::class, 'show']);
    Route::put('borrows/{id}', [BorrowController::class, 'update'])->name('borrows.update');
    Route::post('borrows/return/{id}', [BorrowController::class, 'return']);

    
    // Rating routes
    

        Route::get('/ratings', [RatingController::class, 'index'])->name('ratings.index');
        Route::post('/books/{book}/rate', [RatingController::class, 'store'])->name('ratings.store');
        Route::put('/ratings/{rating}', [RatingController::class, 'update'])->name('ratings.update');
        Route::delete('/ratings/{rating}', [RatingController::class, 'destroy'])->name('ratings.destroy');
    
        

        Route::get('/books/filter', [BookFilterController::class, 'filter']);
        
});
