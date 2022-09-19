<!-- start Sidebar  -->
@if(Route::is('login') || Route::is('register') || request()->is('password/reset'))

@else
    <nav class="sidebar active">
        <ul>
            <!-- coming from App\Helpers\helper.php  -->
        @php
            $category = frontCategory();
        @endphp
        @if(count($category) > 0 )
            @foreach($category as $value)


                <!-- 2nd levelstart-->
                    @foreach($value->sub_category as $sub_cat)
                        <li
                            class="{{ request()->is('product/sub-category/'.$sub_cat->id.'/'.str_replace(' ','-',$sub_cat->sub_category_name))? 'sub_category_active active_color' : '' }}">
                            <a href="{{url('product/sub-category/'.$sub_cat->id.'/'.str_replace(' ','-',$sub_cat->sub_category_name))}}">
                                <x-cld-image public-id="{{ $sub_cat->icon }}" loading="lazy"
                                             width="30" height="30" alt="icon"
                                             class="img-fluid"></x-cld-image>

                                {!! $sub_cat->sub_category_name !!}
                                @if(count($sub_cat->sub_sub_category) > 0) <i class="lni-chevron-right"> @endif</i></a>

                            <!-- third level start -->

                            <!-- third level end -->

                        </li>
                    @endforeach
                <!-- 2nd level end  -->

                @endforeach
            @endif
        </ul>
    </nav>
@endif
<!-- end Sidebar  -->
